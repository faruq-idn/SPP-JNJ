<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    public function verifikasiPembayaran(Request $request, $id)
    {
        $pembayaran = PembayaranSpp::with('metode_pembayaran')->findOrFail($id);

        try {
            DB::beginTransaction();
            $pembayaran->update([
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);
            DB::commit();

            // Load data pembayaran yang diperbarui
            $pembayaran->load('metode_pembayaran');

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi',
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => 'success',
                    'tanggal_bayar' => $pembayaran->tanggal_bayar->format('d/m/Y'),
                    'metode' => $pembayaran->metode_pembayaran->nama,
                    'payment_info' => [
                        'order_id' => $pembayaran->order_id,
                        'transaction_id' => $pembayaran->transaction_id,
                        'payment_type' => $pembayaran->payment_type,
                        'payment_details' => $pembayaran->payment_details,
                        'keterangan' => $pembayaran->keterangan
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Santri $santri)
    {
        $santri->load(['kategori.tarifTerbaru', 'wali']);
        
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');

        $pembayaranPerTahun = [];
        $totalTunggakanPerTahun = [];
        
        // Ambil pembayaran untuk 2 tahun terakhir
        for ($tahun = $tahunSekarang; $tahun >= $tahunSekarang - 1; $tahun--) {
            $pembayaranList = [];
            $tunggakanTahun = 0;
            
            // Tentukan bulan awal berdasarkan tanggal masuk santri
            $bulanAwal = 1;
            $tanggalMasuk = \Carbon\Carbon::parse($santri->tanggal_masuk);
            if ($tahun == $tanggalMasuk->year) {
                $bulanAwal = $tanggalMasuk->month;
            } elseif ($tahun < $tanggalMasuk->year) {
                continue; // Skip tahun sebelum santri masuk
            }
            
            // Generate data hanya untuk bulan yang sudah lewat atau bulan sekarang
            $bulanMaksimal = ($tahun == $tahunSekarang) ? $bulanSekarang : 12;
            
            for ($bulan = $bulanAwal; $bulan <= $bulanMaksimal; $bulan++) {
                $pembayaran = $santri->pembayaran()
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->with('metode_pembayaran')
                    ->first();

                $nominal = $santri->kategori->tarifTerbaru->nominal ?? 0;

                if ($pembayaran) {
                    if ($pembayaran->status !== 'success') {
                        $tunggakanTahun += $pembayaran->nominal;
                    }
                    $pembayaranList[] = $pembayaran;
                } else {
                    // Tambahkan ke tunggakan
                    $tunggakanTahun += $nominal;
                    
                    // Buat object untuk bulan ini
                    $pembayaranList[] = (object)[
                        'bulan' => $bulan,
                        'nama_bulan' => \Carbon\Carbon::create()->month($bulan)->translatedFormat('F'),
                        'nominal' => $nominal,
                        'status' => 'unpaid',
                        'tahun' => $tahun,
                        'metode_pembayaran' => null,
                        'tanggal_bayar' => null
                    ];
                }
            }
            
            if (!empty($pembayaranList)) {
                $pembayaranPerTahun[$tahun] = collect($pembayaranList)->sortByDesc('bulan');
                $totalTunggakanPerTahun[$tahun] = $tunggakanTahun;
            }
        }

        // Hitung total tunggakan keseluruhan
        $totalTunggakan = array_sum($totalTunggakanPerTahun);

        // Hitung status SPP berdasarkan pembayaran tahun ini
        $statusSpp = $this->hitungStatusSpp($santri, $tahunSekarang);

        $metode = MetodePembayaran::all();

        return view('petugas.santri.show', compact(
            'santri',
            'pembayaranPerTahun',
            'statusSpp',
            'totalTunggakan',
            'totalTunggakanPerTahun',
            'metode'
        ));
    }

    public function hapusPembayaran($id)
    {
        $pembayaran = PembayaranSpp::findOrFail($id);

        try {
            DB::beginTransaction();
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
                'message' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()
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
