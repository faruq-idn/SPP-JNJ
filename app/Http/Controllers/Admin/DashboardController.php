<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        Log::info('AdminDashboardController initialized', [
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);
    }

    public function index(Request $request)
    {
        Log::info('Admin dashboard accessed', [
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'session_id' => session()->getId()
        ]);

        try {
            // Get selected month and year or default to current
            $selectedDate = $request->filled(['bulan', 'tahun'])
                ? Carbon::createFromDate($request->tahun, $request->bulan, 1)
                : now();

            $bulanIni = $selectedDate->format('m');
            $tahunIni = $selectedDate->format('Y');

            // Get prev and next month dates
            $prevMonth = (clone $selectedDate)->subMonth();
            $nextMonth = (clone $selectedDate)->addMonth();

            // Don't allow future months
            $canGoNext = $nextMonth->lt(now());

            // Statistik Umum
            $totalSantri = Santri::where('status', 'aktif')->count();
            $totalPetugas = User::where('role', 'petugas')->count();
            $totalWali = User::where('role', 'wali')->count();

            // Statistik per Kategori
            $santriPerKategori = Santri::where('status', 'aktif')
                ->selectRaw('kategori_id, count(*) as total')
                ->groupBy('kategori_id')
                ->with('kategori')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->kategori->nama => $item->total];
                });

            // Statistik per Jenjang
            $santriPerJenjang = Santri::where('status', 'aktif')
                ->selectRaw('jenjang, count(*) as total')
                ->groupBy('jenjang')
                ->pluck('total', 'jenjang')
                ->toArray();

            // Statistik Pembayaran
            $bulanIni = now()->format('m');
            $tahunIni = now()->format('Y');

            $totalPembayaran = PembayaranSpp::where('status', 'success')->count();
            $totalPenerimaan = PembayaranSpp::where('status', 'success')->sum('nominal');
            
            // Total pembayaran bulan yang dipilih (yang sudah lunas)
            $pembayaranBulanIni = PembayaranSpp::query()
                ->where('status', 'success')
                ->where('bulan', $selectedDate->format('m'))
                ->where('tahun', $selectedDate->format('Y'))
                ->sum('nominal');

            // Tunggakan bulan yang dipilih
            $tunggakanBulanIni = PembayaranSpp::query()
                ->whereIn('status', ['pending', 'unpaid'])
                ->where('bulan', $selectedDate->format('m'))
                ->where('tahun', $selectedDate->format('Y'))
                ->sum('nominal');

            // Total tunggakan semua santri
            $totalTunggakan = PembayaranSpp::whereIn('status', ['pending', 'unpaid'])->sum('nominal');

            // Jumlah santri yang menunggak untuk bulan yang dipilih
            $jumlahSantriMenunggak = Santri::whereHas('pembayaran', function($query) use ($selectedDate) {
                    $query->whereIn('status', ['pending', 'unpaid'])
                          ->where('bulan', $selectedDate->format('m'))
                          ->where('tahun', $selectedDate->format('Y'));
                })
                ->where('status', 'aktif')
                ->count();

            // Pembayaran Terbaru (hanya yang lunas)
            $pembayaranTerbaru = PembayaranSpp::with(['santri'])
                ->where('status', 'success')
                ->latest('tanggal_bayar')
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
                'santriPerKategori',
                'pembayaranTerbaru',
                'santriTunggakan',
                'pembayaranBulanIni',
                'tunggakanBulanIni',
                'jumlahSantriMenunggak',
                'selectedDate',
                'prevMonth',
                'nextMonth',
                'canGoNext'
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
