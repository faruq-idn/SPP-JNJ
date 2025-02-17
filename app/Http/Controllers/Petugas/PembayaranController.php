<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $totalBelumLunas = PembayaranSpp::where('status', '!=', 'success')->count();
        $pembayaranPending = PembayaranSpp::with(['santri', 'metode_pembayaran'])
            ->where('status', '!=', 'success')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending')
            ->appends(request()->except('pending'));

        $totalLunas = PembayaranSpp::where('status', 'success')->count();
        $pembayaranLunas = PembayaranSpp::with(['santri', 'metode_pembayaran'])
            ->where('status', 'success')
            ->orderBy('tanggal_bayar', 'desc')
            ->paginate(10, ['*'], 'lunas')
            ->appends(request()->except('lunas'));

        return view('petugas.pembayaran.index', compact(
            'totalBelumLunas',
            'pembayaranPending',
            'totalLunas',
            'pembayaranLunas'
        ));
    }

    public function checkStatus($id)
    {
        try {
            $pembayaran = PembayaranSpp::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
    }

    public function show(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['santri', 'metode_pembayaran']);
        $metode = MetodePembayaran::all();
        return view('petugas.pembayaran.show', compact('pembayaran', 'metode'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = PembayaranSpp::with('santri')->findOrFail($id);
            
            if ($pembayaran->status === 'success') {
                throw new \Exception('Pembayaran ini sudah diverifikasi sebelumnya');
            }

            $pembayaran->update([
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);

            // Update status SPP santri
            $santri = $pembayaran->santri;
            $totalTunggakan = PembayaranSpp::where('santri_id', $santri->id)
                ->where('status', '!=', 'success')
                ->sum('nominal');

            if ($totalTunggakan === 0) {
                $santri->update(['status_spp' => 'lunas']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
