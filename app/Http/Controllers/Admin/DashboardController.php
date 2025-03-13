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
            $totalPembayaran = PembayaranSpp::where('status', 'success')
                ->whereHas('santri', function($query) {
                    $query->where('status', 'aktif');
                })->count();

            $totalPenerimaan = PembayaranSpp::where('status', 'success')
                ->whereHas('santri', function($query) {
                    $query->where('status', 'aktif');
                })->sum('nominal');
            
            // Total pembayaran bulan yang dipilih (yang sudah lunas)
            $pembayaranBulanIni = PembayaranSpp::query()
                ->where('status', 'success')
                ->whereHas('santri', function($query) {
                    $query->where('status', 'aktif');
                })
                ->where('bulan', $selectedDate->format('m'))
                ->where('tahun', $selectedDate->format('Y'))
                ->sum('nominal');

            // Tunggakan bulan yang dipilih (hanya dari pembayaran yang ada)
            $tunggakanBulanIni = PembayaranSpp::where('status', '!=', PembayaranSpp::STATUS_SUCCESS)
                ->where('nominal', '>', 0)
                ->where('bulan', $selectedDate->format('m'))
                ->where('tahun', $selectedDate->format('Y'))
                ->sum('nominal');

            // Total tunggakan (pembayaran belum lunas = belum dibayar = tunggakan)
            $totalTunggakan = PembayaranSpp::where('status', '!=', PembayaranSpp::STATUS_SUCCESS)
                ->where('nominal', '>', 0)
                ->sum('nominal');

            // Jumlah santri yang menunggak untuk bulan yang dipilih (termasuk yang belum ada record pembayaran)
            $jumlahSantriMenunggak = Santri::where('status', 'aktif')
                ->where(function($query) use ($selectedDate) {
                    $query->whereDoesntHave('pembayaran', function($q) use ($selectedDate) {
                        $q->where('bulan', $selectedDate->format('m'))
                          ->where('tahun', $selectedDate->format('Y'))
                          ->where('status', 'success');
                    })
                    ->orWhereHas('pembayaran', function($q) use ($selectedDate) {
                        $q->where('bulan', $selectedDate->format('m'))
                          ->where('tahun', $selectedDate->format('Y'))
                          ->where('status', '!=', 'success')
                          ->where('status', '!=', 'failed');
                    });
                })
                ->count();

            // Pembayaran Terbaru (hanya yang lunas)
            $pembayaranTerbaru = PembayaranSpp::with(['santri'])
                ->where('status', 'success')
                ->latest('tanggal_bayar')
                ->take(5)
                ->get();

            // Santri dengan Tunggakan Terbanyak
            $santriTunggakan = Santri::where('status', 'aktif')
                ->with(['pembayaran' => function($query) {
                    $query->where('status', '!=', PembayaranSpp::STATUS_SUCCESS)
                        ->where('nominal', '>', 0);
                }])
                ->whereHas('pembayaran', function($query) {
                    $query->where('status', '!=', PembayaranSpp::STATUS_SUCCESS)
                        ->where('nominal', '>', 0);
                })
                ->get()
                ->map(function($santri) {
                    // Hitung total tunggakan hanya dari pembayaran yang belum lunas
                    $santri->total_tunggakan = $santri->pembayaran->sum('nominal');
                    $santri->jumlah_bulan_tunggakan = $santri->pembayaran->count();
                    return $santri;
                })
                ->sortByDesc('total_tunggakan')
                ->take(5)
                ->values();

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

