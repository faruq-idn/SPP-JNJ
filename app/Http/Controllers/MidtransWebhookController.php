<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Midtrans notification received', [
            'payload' => $request->all()
        ]);

        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Validasi signature key
        if ($hashed != $request->signature_key) {
            Log::warning('Invalid signature key', [
                'received' => $request->signature_key,
                'calculated' => $hashed
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature'
            ], 400);
        }

        try {
            // Cari pembayaran berdasarkan order_id
            $pembayaran = PembayaranSpp::where('order_id', $request->order_id)->first();
            
            if (!$pembayaran) {
                Log::error('Payment not found', [
                    'order_id' => $request->order_id
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found'
                ], 404);
            }

            // Verifikasi jumlah pembayaran
            $amount = (int) $request->gross_amount;
            if ($amount !== $pembayaran->nominal) {
                Log::error('Invalid payment amount', [
                    'expected' => $pembayaran->nominal,
                    'received' => $amount,
                    'order_id' => $request->order_id
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid payment amount'
                ], 400);
            }

            // Update status pembayaran berdasarkan notifikasi Midtrans
            switch ($request->transaction_status) {
                case 'capture':
                case 'settlement':
                    if ($request->fraud_status == 'accept') {
                        $status = PembayaranSpp::STATUS_SUCCESS;
                    } else {
                        $status = PembayaranSpp::STATUS_FAILED;
                    }
                    break;
                case 'pending':
                    $status = PembayaranSpp::STATUS_PENDING;
                    break;
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    $status = PembayaranSpp::STATUS_FAILED;
                    break;
                default:
                    $status = PembayaranSpp::STATUS_FAILED;
            }

            // Update data pembayaran
            $pembayaran->update([
                'status' => $status,
                'tanggal_bayar' => now(),
                'payment_type' => $request->payment_type,
                'transaction_id' => $request->transaction_id,
                'payment_details' => [
                    'payment_type' => $request->payment_type,
                    'bank' => $request->bank ?? null,
                    'va_number' => $request->va_numbers[0]->va_number ?? null,
                    'store' => $request->store ?? null,
                    'permata_va_number' => $request->permata_va_number ?? null,
                    'bill_key' => $request->bill_key ?? null,
                    'biller_code' => $request->biller_code ?? null
                ],
                'fraud_status' => $request->fraud_status
            ]);

            Log::info('Payment status updated', [
                'order_id' => $request->order_id,
                'status' => $status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notification handled'
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
