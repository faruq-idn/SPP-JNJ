<?php

namespace App\Http\Controllers\Petugas;

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
            'pembayaranHariIni' => PembayaranSpp::whereDate('created_at', today())->count(),
            'pembayaranTerbaru' => PembayaranSpp::with('santri')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return response()
            ->view('petugas.dashboard', $data)
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
