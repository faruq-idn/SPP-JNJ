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
        if (session()->has('selected_santri_id')) {
            $santri = $santri_list->where('id', session('selected_santri_id'))->first();
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

        // Ambil bulan dan tahun sekarang
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Ambil pembayaran dan kelompokkan per tahun
        $pembayaran = PembayaranSpp::where('santri_id', $santri->id)
            ->select('id', 'bulan', 'tahun', 'nominal', 'status', 'tanggal_bayar', 'metode_pembayaran_id')
            ->with('metode_pembayaran')
            ->get()
            ->map(function ($item) use ($currentMonth, $currentYear) {
                // Konversi bulan dari angka ke nama
                $bulanNum = str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                $item->nama_bulan = \Carbon\Carbon::createFromFormat('m', $bulanNum)->translatedFormat('F');
                
                // Hitung prioritas urutan
                if ($item->tahun == $currentYear && $item->bulan == $currentMonth) {
                    $item->urutan = 1; // Bulan sekarang paling atas
                } elseif ($item->status != 'success') {
                    $item->urutan = 2; // Belum lunas kedua
                } else {
                    $item->urutan = 3; // Sudah lunas terakhir
                }
                
                return $item;
            })
            ->sortBy([
                ['urutan', 'asc'],           // Urutkan berdasarkan prioritas
                ['tahun', 'desc'],           // Tahun terbaru
                ['bulan', 'desc'],           // Bulan terbaru dalam tahun yang sama
                ['status', function($a, $b) { // Unpaid -> Pending -> Success
                    $statusOrder = ['unpaid' => 1, 'pending' => 2, 'success' => 3];
                    return $statusOrder[$a] <=> $statusOrder[$b];
                }]
            ])
            ->groupBy('tahun');

        $pembayaranPerTahun = $pembayaran;

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
