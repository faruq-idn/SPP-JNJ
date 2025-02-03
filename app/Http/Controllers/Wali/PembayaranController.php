<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class PembayaranController extends Controller
{
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

        // Validasi signature menggunakan HMAC-SHA512 sesuai dokumentasi Midtrans
        $mySignatureKey = hash_hmac('sha512', $orderId . $statusCode . $grossAmount, $serverKey);

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

        // Validasi amount sesuai dengan database
        if ((int)$grossAmount !== (int)$pembayaran->nominal) {
            Log::error('Jumlah pembayaran tidak sesuai: ' . $grossAmount . ' vs ' . $pembayaran->nominal, [
                'payload' => $payload,
                'pembayaran' => $pembayaran
            ]);
            return response()->json(['message' => 'Invalid payment amount'], 400);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $type = $payload['payment_type'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';

        // Update status pembayaran
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
