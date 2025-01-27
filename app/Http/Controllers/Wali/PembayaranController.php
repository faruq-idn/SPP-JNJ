<?php

namespace App\Http\Controllers\Wali;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tagihan_id' => 'required|exists:pembayaran_spp,id',
        ]);

        $pembayaran = PembayaranSpp::findOrFail($validatedData['tagihan_id']);

        // Generate unique order ID using UUID
        $order_id = Str::uuid();

        // Update pembayaran dengan order_id baru
        $pembayaran->update([
            'order_id' => $order_id
        ]);

        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Siapkan item untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int) $pembayaran->nominal,
            ],
            'customer_details' => [
                'first_name' => $pembayaran->santri->nama,
                'email' => $pembayaran->santri->wali->email ?? '',
                'phone' => $pembayaran->santri->wali->no_hp ?? '',
            ],
            'item_details' => [
                [
                    'id' => $pembayaran->id,
                    'price' => (int) $pembayaran->nominal,
                    'quantity' => 1,
                    'name' => "SPP {$pembayaran->nama_bulan} {$pembayaran->tahun}",
                ]
            ],
            // Tambahkan callback URLs
            'callbacks' => [
                'finish' => route('wali.pembayaran.success'),
                'error' => route('wali.pembayaran.error'),
                'cancel' => route('wali.tagihan') // Tetap ke halaman tagihan jika dibatalkan
            ],
            // Tambahkan notification URL
            'notification_url' => config('midtrans.notification_url')
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Update pembayaran dengan snap token
            $pembayaran->update([
                'snap_token' => $snapToken,
                'status' => 'pending'
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'message' => 'Berhasil membuat transaksi'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi Midtrans: ' . $e->getMessage(), [
                'exception' => $e,
                'pembayaran_id' => $request->tagihan_id,
                'order_id' => $order_id ?? null
            ]);
            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();

        // Log payload notifikasi yang diterima dari Midtrans
        Log::info('Midtrans Notification Payload:', $payload);

        $signatureKey = $payload['signature_key'] ?? '';
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');

        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignatureKey) {
            // Log error validasi signature key gagal
            Log::error('Midtrans Notification Signature Key Validation Failed:', [
                'payload' => $payload,
                'signatureKey' => $signatureKey,
                'mySignatureKey' => $mySignatureKey
            ]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $pembayaran = PembayaranSpp::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $type = $payload['payment_type'] ?? '';

        // Update status pembayaran
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $pembayaran->update([
                    'status' => 'success',
                    'tanggal_bayar' => now(),
                    'payment_type' => $type,
                    'transaction_id' => $payload['transaction_id'] ?? null,
                    'payment_details' => $payload
                ]);

                // Update status SPP santri
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

        try {
            return response()->json(['message' => 'Notifikasi berhasil diproses']);
        } catch (\Exception $e) {
            Log::error('Gagal memproses notifikasi Midtrans: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $payload
            ]);
            return response()->json(['message' => 'Gagal memproses notifikasi'], 500);
        }
    }

    private function updateSantriSppStatus($santri_id)
    {
        $santri = \App\Models\Santri::find($santri_id);
        if (!$santri) return;

        // Cek apakah ada pembayaran yang belum lunas
        $unpaidCount = PembayaranSpp::where('santri_id', $santri_id)
            ->where('status', '!=', 'success')
            ->count();

        // Update status SPP santri
        $santri->update([
            'status_spp' => $unpaidCount === 0 ? 'Lunas' : 'Belum Lunas'
        ]);
    }
}
