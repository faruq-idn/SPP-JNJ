<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data = [
            'totalSantri' => Santri::count(),
            'totalPenerimaan' => PembayaranSpp::where('status', 'success')->sum('nominal'),
            'totalTunggakan' => 0, // Hitung total tunggakan
            'pembayaranHariIni' => PembayaranSpp::whereDate('created_at', today())->count(),
            'pembayaranTerbaru' => PembayaranSpp::with('santri')
                ->latest()
                ->take(5)
                ->get(),
            'notifications' => [], // Data notifikasi
        ];

        return view('dashboard.admin', $data);
    }

    public function petugas()
    {
        return view('dashboard.petugas');
    }

    public function wali()
    {
        $santri = Santri::where('wali_id', Auth::id())->first();
        $tagihan = 0; // Hitung tagihan di sini

        return view('dashboard.wali', compact('santri', 'tagihan'));
    }
}
