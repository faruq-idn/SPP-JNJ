<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;

class DashboardController extends Controller
{
    public function index()
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

        return view('admin.dashboard', $data);
    }
}
