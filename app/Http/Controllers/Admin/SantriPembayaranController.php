<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SantriPembayaranController extends Controller
{
    public function show(Santri $santri)
    {
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');

        $pembayaranPerTahun = [];
        $totalTunggakanPerTahun = [];
        
        // Ambil pembayaran untuk 2 tahun terakhir
        for ($tahun = $tahunSekarang; $tahun >= $tahunSekarang - 1; $tahun--) {
            $pembayaranList = [];
            $tunggakanTahun = 0;
            
            // Generate data untuk 12 bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pembayaran = $santri->pembayaran()
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->with('metode_pembayaran')
                    ->first();

                if ($pembayaran) {
                    if ($pembayaran->status !== 'success') {
                        $tunggakanTahun += $pembayaran->nominal;
                    }
                    $pembayaranList[] = $pembayaran;
                } else {
                    // Jika tidak ada pembayaran dan bulan sudah lewat, hitung sebagai tunggakan
                    $nominal = $santri->kategori->tarifTerbaru->nominal ?? 0;
                    if ($tahun < $tahunSekarang || ($tahun == $tahunSekarang && $bulan <= $bulanSekarang)) {
                        $tunggakanTahun += $nominal;
                    }
                    
                    // Buat object untuk bulan ini
                    $pembayaranList[] = (object)[
                        'bulan' => sprintf('%02d', $bulan),
                        'nominal' => $nominal,
                        'status' => 'unpaid',
                        'tahun' => $tahun,
                        'nama_bulan' => Carbon::create()->month($bulan)->translatedFormat('F')
                    ];
                }
            }
            
            $pembayaranPerTahun[$tahun] = collect($pembayaranList)->sortByDesc(function ($pembayaran) {
                return $pembayaran->status === 'unpaid' ? 1 : 0;
            });
            $totalTunggakanPerTahun[$tahun] = $tunggakanTahun;
        }

        // Hitung total tunggakan
        $totalTunggakan = $santri->pembayaran()
            ->whereIn('status', ['unpaid', 'pending'])
            ->sum('nominal');

        // Hitung status SPP
        $statusSpp = $this->hitungStatusSpp($santri, $tahunSekarang);

        // Ambil riwayat perubahan tarif
        $riwayatTarif = $santri->kategori->riwayatTarif()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.santri.show', compact(
            'santri',
            'pembayaranPerTahun',
            'totalTunggakan',
            'totalTunggakanPerTahun',
            'statusSpp',
            'riwayatTarif'
        ));
    }

    public function verifikasi(Request $request, PembayaranSpp $pembayaran)
    {
        $request->validate([
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);

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

    public function hapus(PembayaranSpp $pembayaran)
    {
        if ($pembayaran->status === 'success') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat menghapus pembayaran yang sudah lunas'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $pembayaran->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tambah(Request $request, Santri $santri)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah sudah ada pembayaran
            $exists = PembayaranSpp::where('santri_id', $santri->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if ($exists) {
                throw new \Exception('Pembayaran untuk periode ini sudah ada');
            }

            PembayaranSpp::create([
                'santri_id' => $santri->id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'nominal' => $request->nominal,
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'status' => 'success',
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function hitungStatusSpp($santri, $tahun)
    {
        $pembayaranTahunIni = $santri->pembayaran()
            ->where('tahun', $tahun)
            ->whereMonth('created_at', '<=', now()->month)
            ->get();

        if ($pembayaranTahunIni->isEmpty()) {
            return 'Belum Lunas';
        }

        return $pembayaranTahunIni->every(function ($pembayaran) {
            return $pembayaran->status === 'success';
        }) ? 'Lunas' : 'Belum Lunas';
    }
}
