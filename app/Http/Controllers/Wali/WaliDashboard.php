<?php

namespace App\Http\Controllers\Wali;

use App\Models\PembayaranSpp;
use App\Models\Santri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WaliDashboard extends Controller
{
    public function index()
    {
        // Ambil semua santri yang dimiliki wali dengan kategori dan tarif
        $santri_list = Santri::where('wali_id', Auth::id())
            ->with([
                'kategori.tarifTerbaru',
                'kategori' => function($query) {
                    $query->with(['tarifTerbaru' => function($q) {
                        $q->whereNull('berlaku_sampai');
                    }]);
                }
            ])
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
                ->with('error', 'Silakan hubungkan data santri terlebih dahulu');
        }

        // Ambil pembayaran tahun ini untuk status SPP
        $tahunIni = date('Y');
        $pembayaranTahunIni = PembayaranSpp::where('santri_id', $santri->id)
            ->where('tahun', $tahunIni)
            ->get();
        
        // Hitung status SPP
        if ($pembayaranTahunIni->isEmpty()) {
            $statusSpp = 'Belum Lunas';
        } else {
            $statusSpp = $pembayaranTahunIni->every(function ($pembayaran) {
                return $pembayaran->status == 'success';
            }) ? 'Lunas' : 'Belum Lunas';
        }

        // Update status SPP santri
        $santri->status_spp = $statusSpp;
        $santri->save();

        // Ambil pembayaran yang belum lunas
        $pembayaran_terbaru = PembayaranSpp::where('santri_id', $santri->id)
            ->where(function($query) {
                $query->where('status', 'unpaid')
                      ->orWhere('status', 'pending');
            })
            ->orderBy('tahun', 'asc')
            ->orderByRaw("FIELD(bulan, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12)")
            ->get();

        // Hitung total tunggakan (status unpaid dan pending)
        $total_tunggakan = PembayaranSpp::where('santri_id', $santri->id)
            ->where(function($query) {
                $query->where('status', 'unpaid')
                      ->orWhere('status', 'pending');
            })
            ->sum('nominal');

        return view('wali.dashboard', compact('santri', 'santri_list', 'pembayaran_terbaru', 'total_tunggakan'));
    }

    public function changeSantri(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id'
        ]);

        $santri = Santri::where('id', $request->santri_id)
            ->where('wali_id', Auth::id())
            ->firstOrFail();

        session(['santri_aktif' => $santri->id]);

        return redirect()->back();
    }
}
