<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \Midtrans\Config as MidtransConfig;
use \Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        if (!config('midtrans.server_key')) {
            throw new \Exception('Midtrans Server Key tidak ditemukan');
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        Log::info('Midtrans Config:', [
            'server_key_exists' => !empty(config('midtrans.server_key')),
            'is_production' => config('midtrans.is_production')
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tagihan_id' => 'required|exists:pembayaran_spp,id'
            ]);

            $tagihan = PembayaranSpp::with(['santri.wali'])
                ->whereHas('santri', function($query) {
                    $query->where('wali_id', Auth::id());
                })
                ->find($validated['tagihan_id']);

            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan atau bukan milik Anda');
            }

            // Validasi data wali
            if (!$tagihan->santri->wali->email || !$tagihan->santri->wali->no_hp) {
                throw new \Exception('Mohon lengkapi data email dan nomor HP di profil Anda');
            }

            // Cek status tagihan
            if ($tagihan->status === 'success') {
                throw new \Exception('Tagihan ini sudah dibayar');
            }

            // Generate order ID baru jika belum ada atau sudah expired
            if (!$tagihan->order_id || $tagihan->status === 'expired') {
                $tagihan->order_id = 'SPP-' . $tagihan->id . '-' . time();
                $tagihan->save();
            }

            // Setup transaksi Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $tagihan->order_id,
                    'gross_amount' => (int) $tagihan->nominal
                ],
                'customer_details' => [
                    'first_name' => $tagihan->santri->wali->name,
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
                'callbacks' => [
                    'finish' => route('wali.tagihan'),
                    'error' => route('wali.tagihan'),
                    'cancel' => route('wali.tagihan')
                ]
            ];

            Log::info('Creating Midtrans transaction', [
                'order_id' => $tagihan->order_id,
                'params' => $params
            ]);

            try {
                $snapToken = Snap::getSnapToken($params);

                // Update snap token
                $tagihan->update([
                    'snap_token' => $snapToken,
                    'status' => 'pending'
                ]);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken
                ]);
            } catch (\Exception $e) {
                Log::error('Midtrans Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Gagal membuat transaksi: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notification = $request->all();

            Log::info('Payment notification received', ['data' => $notification]);

            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $paymentType = $notification['payment_type'];
            $transactionId = $notification['transaction_id'];

            // Cari pembayaran berdasarkan order_id
            $pembayaran = PembayaranSpp::where('order_id', $orderId)->firstOrFail();

            // Update status pembayaran
            $status = match($transactionStatus) {
                'capture', 'settlement' => 'success',
                'pending' => 'pending',
                'deny', 'cancel', 'expire' => 'expired',
                default => 'failed'
            };

            $pembayaran->update([
                'status' => $status,
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
                'payment_details' => $notification,
                'tanggal_bayar' => $status === 'success' ? now() : null
            ]);

            Log::info('Payment status updated', [
                'order_id' => $orderId,
                'status' => $status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Notification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }
}
