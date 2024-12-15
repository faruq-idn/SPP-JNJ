<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalSantri' => Santri::count(),
            'totalPenerimaan' => PembayaranSpp::where('status', 'success')->sum('nominal'),
            'totalTunggakan' => 0,
            'pembayaranHariIni' => PembayaranSpp::whereDate('created_at', today())->count(),
            'pembayaranTerbaru' => PembayaranSpp::with('santri')
                ->latest()
                ->take(5)
                ->get(),
            'notifications' => [],
        ];

        return view('admin.dashboard', $data);
    }

    public function petugas()
    {
        $data = [
            'totalSantri' => Santri::count(),
            'totalPenerimaan' => PembayaranSpp::where('status', 'success')->sum('nominal'),
            'pembayaranHariIni' => PembayaranSpp::whereDate('created_at', today())->count(),
            'pembayaranTerbaru' => PembayaranSpp::with('santri')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('petugas.dashboard', $data);
    }

    public function wali()
    {
        $santri = Santri::where('wali_id', Auth::id())->first();
        $pembayaran = PembayaranSpp::where('santri_id', $santri->id ?? 0)
            ->latest()
            ->take(5)
            ->get();

        return view('wali.dashboard', compact('santri', 'pembayaran'));
    }
}
