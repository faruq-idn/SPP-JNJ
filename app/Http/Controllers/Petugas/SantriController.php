<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    public function show(Santri $santri)
    {
        $santri->load(['kategori.tarifTerbaru', 'wali']);
        
        // Get riwayat perubahan tarif
        $riwayatTarif = $santri->kategori->riwayatTarif()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get metode pembayaran
        $metode = MetodePembayaran::all();

        // Get pembayaran untuk tiap tahun
        $pembayaran = PembayaranSpp::with(['metode_pembayaran'])
            ->where('santri_id', $santri->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // Group pembayaran by tahun
        $pembayaranPerTahun = $pembayaran->groupBy('tahun');

        // Hitung tunggakan per tahun
        $totalTunggakanPerTahun = [];
        $totalTunggakan = 0;
        $tahunSekarang = date('Y');

        foreach ($pembayaranPerTahun as $tahun => $pembayaranList) {
            // Hitung tunggakan untuk tahun ini
            $tunggakanTahun = $pembayaranList
                ->where('status', '!=', 'success')
                ->sum('nominal');

            $totalTunggakanPerTahun[$tahun] = $tunggakanTahun;
            $totalTunggakan += $tunggakanTahun;
        }

        // Set status SPP berdasarkan tunggakan
        $statusSpp = 'Lunas';
        if ($totalTunggakan > 0) {
            $statusSpp = 'Belum Lunas';
        }

        return view('petugas.santri.show', compact(
            'santri',
            'riwayatTarif',
            'pembayaranPerTahun',
            'statusSpp',
            'totalTunggakan',
            'totalTunggakanPerTahun',
            'metode'
        ));
    }
}
