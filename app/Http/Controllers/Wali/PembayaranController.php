<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Log::info('Midtrans config', [
            'server_key' => config('midtrans.server_key'),
            'is_production' => config('midtrans.is_production'),
            'merchant_id' => config('midtrans.merchant_id')
        ]);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$appendNotifUrl = route('wali.pembayaran.notification');
        Config::$overrideNotifUrl = true;

        // Handle SSL certificate
        if (config('midtrans.ignore_ssl')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ];
        } else {
            $caInfo = env('CURL_CA_BUNDLE');
            if ($caInfo && file_exists($caInfo)) {
                Config::$curlOptions = [
                    CURLOPT_CAINFO => $caInfo
                ];
            }
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->json()->all();
            $tagihan_id = $data['tagihan_id'] ?? null;

            if (!$tagihan_id) {
                throw new \Exception('ID tagihan tidak ditemukan');
            }

            Log::info('Starting payment process', ['tagihan_id' => $tagihan_id]);

            $request->validate([
                'tagihan_id' => 'required|exists:pembayaran_spp,id'
            ]);

            // Verifikasi tagihan milik santri dari wali yang login
            $tagihan = PembayaranSpp::with(['santri.wali'])
                ->whereHas('santri', function($query) {
                    $query->where('wali_id', Auth::id());
                })
                ->findOrFail($tagihan_id);

            // Cek status tagihan
            if ($tagihan->status === 'success') {
                throw new \Exception('Tagihan ini sudah dibayar');
            }

            // Cek apakah sudah ada snap token yang masih valid
            if ($tagihan->snap_token) {
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $tagihan->snap_token
                ]);
            }

            Log::info('Tagihan found', ['tagihan' => $tagihan->toArray()]);

            // Validasi data wali
            if (!$tagihan->santri->wali) {
                throw new \Exception('Data wali santri tidak ditemukan');
            }

            $wali = $tagihan->santri->wali;
            $errors = [];

            if (empty($wali->email)) {
                $errors[] = 'Email';
            }
            if (empty($wali->no_hp)) {
                $errors[] = 'Nomor HP';
            }

            if (!empty($errors)) {
                throw new \Exception(
                    'Mohon lengkapi data wali santri terlebih dahulu: ' . implode(', ', $errors)
                );
            }

            // Setup transaksi Midtrans
            $orderId = 'SPP-' . $tagihan->id . '-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $tagihan->nominal
                ],
                'customer_details' => [
                    'first_name' => $tagihan->santri->wali->nama,
                    'email' => $tagihan->santri->wali->email,
                    'phone' => $tagihan->santri->wali->no_hp
                ],
                'item_details' => [
                    [
                        'id' => 'SPP-' . $tagihan->id,
                        'price' => (int) $tagihan->nominal,
                        'quantity' => 1,
                        'name' => 'Pembayaran SPP ' . $tagihan->bulan . '/' . $tagihan->tahun
                    ]
                ],
            ];

            Log::info('Midtrans params', ['params' => $params]);

            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                Log::error('Midtrans error', [
                    'message' => $e->getMessage(),
                    'params' => $params,
                    'trace' => $e->getTraceAsString(),
                    'config' => [
                        'server_key' => Config::$serverKey,
                        'is_production' => Config::$isProduction,
                        'curl_options' => Config::$curlOptions
                    ]
                ]);
                throw new \Exception('Gagal membuat transaksi Midtrans: ' . $e->getMessage());
            }

            // Update tagihan dengan snap token
            $tagihan->update([
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);

            Log::info('Payment token generated', [
                'order_id' => $orderId,
                'snap_token' => $snapToken
            ]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        Log::info('Midtrans notification received', ['payload' => $request->all()]);

        try {
            $notification = $request->all();
            if (empty($notification)) {
                throw new \Exception('Empty notification data');
            }

            Log::info('Processing notification', ['notification' => $notification]);

            $orderId = $notification['order_id'];
            $statusCode = $notification['status_code'];
            $grossAmount = $notification['gross_amount'];

            $serverKey = config('midtrans.server_key');
            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signature !== $notification['signature_key']) {
                Log::warning('Invalid signature', [
                    'calculated' => $signature,
                    'received' => $notification['signature_key']
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $pembayaran = PembayaranSpp::where('order_id', $orderId)->firstOrFail();

            Log::info('Found payment', ['pembayaran' => $pembayaran->toArray()]);

            if ($notification['transaction_status'] == 'settlement') {
                Log::info('Payment success', ['order_id' => $orderId]);
                $pembayaran->update([
                    'status' => 'success',
                    'tanggal_bayar' => now(),
                    'payment_type' => $notification['payment_type'],
                    'transaction_id' => $notification['transaction_id'],
                    'payment_details' => json_encode($notification)
                ]);
                Log::info('Payment updated successfully');
            } elseif (in_array($notification['transaction_status'], ['deny', 'cancel', 'expire'])) {
                Log::info('Payment failed', ['order_id' => $orderId, 'status' => $notification['transaction_status']]);
                $pembayaran->update([
                    'status' => 'failed',
                    'payment_details' => json_encode($notification)
                ]);
                Log::info('Payment marked as failed');
            }

            return response()->json(['message' => 'Notification handled']);
        } catch (\Exception $e) {
            Log::error('Notification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
