<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function index()
    {
        // Ambil daftar santri yang terhubung dengan wali
        $santri_list = Santri::where('wali_id', Auth::id())
            ->with(['kategori.riwayatTarif' => function ($query) {
                $query->latest();
            }])
            ->get();

        // Ambil santri aktif dari session atau ambil yang pertama
        $santri = null;
        if (session()->has('santri_aktif')) {
            $santri = $santri_list->where('id', session('santri_aktif'))->first();
        }

        if (!$santri && $santri_list->isNotEmpty()) {
            $santri = $santri_list->first();
        }

        if (!$santri || $santri_list->isEmpty()) {
            return redirect()->route('wali.hubungkan')
                ->with('error', 'Silakan hubungkan akun dengan santri terlebih dahulu');
        }

        // Ambil tarif terbaru
        $tarif = $santri->kategori->riwayatTarif()->latest()->first();

        // Ambil pembayaran dan kelompokkan per tahun
        $pembayaranPerTahun = PembayaranSpp::where('santri_id', $santri->id)
            ->select('id', 'bulan', 'tahun', 'nominal', 'status', 'tanggal_bayar')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'asc')
            ->get()
            ->groupBy('tahun');

        // Hitung total tunggakan (termasuk status unpaid dan pending)
        $totalTunggakan = PembayaranSpp::where('santri_id', $santri->id)
            ->whereIn('status', ['unpaid', 'pending'])
            ->sum('nominal');

        // Tentukan status SPP
        // Hitung status SPP
        $tahunIni = date('Y');
        $pembayaranTahunIni = $pembayaranPerTahun->get($tahunIni) ?? collect();
        
        if ($pembayaranTahunIni->isEmpty()) {
            $statusSpp = 'Belum Lunas';
        } else {
            $statusSpp = $pembayaranTahunIni->every(function ($pembayaran) {
                return $pembayaran->status == 'success';
            }) ? 'Lunas' : 'Belum Lunas';
        }

        // Update status SPP di database
        $santri->status_spp = $statusSpp;
        $santri->save();

        return response()
            ->view('wali.tagihan.index', compact(
                'santri',
                'santri_list',
                'pembayaranPerTahun',
                'totalTunggakan',
                'statusSpp',
                'tarif'
            ))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
