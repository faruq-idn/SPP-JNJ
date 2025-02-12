<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
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
            
            // Generate data hanya untuk bulan yang sudah lewat atau bulan sekarang
            $bulanMaksimal = ($tahun == $tahunSekarang) ? $bulanSekarang : 12;
            
            for ($bulan = 1; $bulan <= $bulanMaksimal; $bulan++) {
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
                $pembayaranPerTahun[$tahun] = collect($pembayaranList)->sortBy('bulan');
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
}
