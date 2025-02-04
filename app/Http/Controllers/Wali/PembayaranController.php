<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        try {
            // Validasi konfigurasi Midtrans
            if (empty(config('midtrans.server_key'))) {
                throw new \Exception('Konfigurasi Midtrans tidak ditemukan');
            }

            // Validasi nomor HP wali santri
            $user = Auth::user();
            if (!$user->no_hp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda harus menambahkan nomor HP terlebih dahulu',
                    'redirect_url' => route('wali.profil')
                ], 400);
            }

            $validatedData = $request->validate([
                'tagihan_id' => 'required|exists:pembayaran_spp,id',
            ]);

            $pembayaran = PembayaranSpp::findOrFail($validatedData['tagihan_id']);

            $order_id = 'SPP-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => (int) $pembayaran->nominal,
                ],
                'customer_details' => [
                    'first_name' => $pembayaran->santri->nama,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->no_hp,
                ],
                'callbacks' => [
                    'finish' => route('wali.pembayaran.success'),
                    'error' => route('wali.pembayaran.error'),
                    'cancel' => route('wali.tagihan'),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update order_id di database
            $pembayaran->update([
                'order_id' => $order_id
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order_id
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification:', $payload);

        $signatureKey = $payload['signature_key'] ?? '';
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');

        $mySignatureKey = hash_hmac('sha512', $orderId . $statusCode . $grossAmount . $serverKey, $serverKey);

        if ($signatureKey !== $mySignatureKey) {
            Log::error('Invalid Signature Key', [
                'received' => $signatureKey,
                'calculated' => $mySignatureKey
            ]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $pembayaran = PembayaranSpp::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $type = $payload['payment_type'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $pembayaran->update([
                    'status' => 'success',
                    'tanggal_bayar' => now(),
                    'payment_type' => $type,
                    'fraud_status' => $fraudStatus,
                    'transaction_id' => $payload['transaction_id'] ?? null,
                    'payment_details' => $payload
                ]);
                $this->updateSantriSppStatus($pembayaran->santri_id);
                break;

            case 'pending':
                $pembayaran->update([
                    'status' => 'pending',
                    'payment_type' => $type,
                    'payment_details' => $payload
                ]);
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $pembayaran->update([
                    'status' => 'unpaid',
                    'payment_details' => $payload
                ]);
                break;
        }

        return response()->json(['message' => 'Notification processed']);
    }

    private function updateSantriSppStatus($santri_id)
    {
        $santri = \App\Models\Santri::find($santri_id);
        if (!$santri) return;

        // Ambil semua pembayaran tahun ini
        $currentYear = date('Y');
        $unpaidCount = PembayaranSpp::where('santri_id', $santri_id)
            ->whereYear('created_at', $currentYear)
            ->where(function($query) {
                $query->where('status', 'unpaid')
                    ->orWhere('status', 'pending');
            })
            ->count();

        // Update status SPP santri
        $santri->update([
            'status_spp' => $unpaidCount === 0 ? 'Lunas' : 'Belum Lunas'
        ]);
    }
}
