<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Umum
        $totalSantri = Santri::where('status', 'aktif')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalWali = User::where('role', 'wali')->count();

        // Statistik Pembayaran
        $totalPembayaran = PembayaranSpp::where('status', 'success')->count();
        $totalPenerimaan = PembayaranSpp::where('status', 'success')->sum('nominal');
        $totalTunggakan = PembayaranSpp::where('status', 'pending')->sum('nominal');

        // Statistik per Jenjang
        $santriPerJenjang = Santri::where('status', 'aktif')
            ->selectRaw('jenjang, count(*) as total')
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang')
            ->toArray();

        // Pembayaran Terbaru
        $pembayaranTerbaru = PembayaranSpp::with(['santri', 'metode_pembayaran'])
            ->latest()
            ->take(5)
            ->get();

        // Santri dengan Tunggakan Terbanyak
        $santriTunggakan = Santri::withCount(['pembayaran' => function($query) {
                $query->where('status', 'pending');
            }])
            ->having('pembayaran_count', '>', 0)
            ->orderByDesc('pembayaran_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSantri',
            'totalPetugas',
            'totalWali',
            'totalPembayaran',
            'totalPenerimaan',
            'totalTunggakan',
            'santriPerJenjang',
            'pembayaranTerbaru',
            'santriTunggakan'
        ));
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
