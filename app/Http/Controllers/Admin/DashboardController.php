<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        Log::info('AdminDashboardController initialized', [
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);
    }

    public function index()
    {
        Log::info('Admin dashboard accessed', [
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'session_id' => session()->getId()
        ]);

        try {
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
            $pembayaranTerbaru = PembayaranSpp::with(['santri'])
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

            Log::info('Admin dashboard data loaded successfully', [
                'total_santri' => $totalSantri,
                'total_pembayaran' => $totalPembayaran,
                'session_id' => session()->getId()
            ]);

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

        } catch (\Exception $e) {
            Log::error('Error loading admin dashboard', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }
}
