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

            // Tunggakan bulan yang dipilih
            $tunggakanBulanIni = 0;

            $santriAktifBulanIni = Santri::where('status', 'aktif')
                ->with(['kategori.tarifTerbaru', 'pembayaran'])
                ->where('tanggal_masuk', '<=', $selectedDate->endOfMonth())
                ->get();

            foreach ($santriAktifBulanIni as $santri) {
                if (!$santri->kategori || !$santri->kategori->tarifTerbaru) continue;

                // Cek pembayaran untuk bulan yang dipilih
                $pembayaranBulan = $santri->pembayaran
                    ->where('bulan', $selectedDate->format('m'))
                    ->where('tahun', $selectedDate->format('Y'))
                    ->first();

                // Ambil tarif yang berlaku
                $tarifBulanIni = $santri->kategori->tarifTerbaru->nominal;

                if (!$pembayaranBulan || $pembayaranBulan->status === 'failed') {
                    // Jika belum ada pembayaran atau gagal
                    $tunggakanBulanIni += $tarifBulanIni;
                } elseif ($pembayaranBulan->status !== 'success') {
                    // Jika ada pembayaran tapi belum lunas
                    $tunggakanBulanIni += ($tarifBulanIni - $pembayaranBulan->nominal);
                }
            }

            // Total tunggakan dari seluruh santri (aktif/lulus/keluar)
            $totalTunggakan = 0;
            
            // Ambil semua santri dengan relasi yang diperlukan
            $santriAll = Santri::with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
                    $query->where(function($q) {
                        $q->where('status', '!=', 'success')
                          ->where('status', '!=', 'failed');
                    })->orWhere('status', 'success');
                }])
                ->get();

            foreach ($santriAll as $santri) {
                if (!$santri->kategori || !$santri->kategori->tarifTerbaru) continue;

                $tarifSpp = $santri->kategori->tarifTerbaru->nominal;
                
                // Hitung periode aktif santri
                $bulanMasuk = Carbon::parse($santri->tanggal_masuk)->startOfMonth();
                $bulanSekarang = Carbon::now()->startOfMonth();
                
                // Generate semua bulan yang seharusnya dibayar
                $periode = collect();
                $currentMonth = clone $bulanMasuk;
                
                while ($currentMonth <= $bulanSekarang) {
                    $periode->push([
                        'bulan' => $currentMonth->format('m'),
                        'tahun' => $currentMonth->format('Y')
                    ]);
                    $currentMonth->addMonth();
                }
                
                // Pembayaran yang sudah dilakukan
                $pembayaranLunas = $santri->pembayaran
                    ->where('status', 'success')
                    ->map(function($payment) {
                        return $payment->bulan . '-' . $payment->tahun;
                    })
                    ->toArray();
                
                // Hitung tunggakan
                if ($santri->kategori && $santri->kategori->tarifTerbaru) {
                    $tarifSpp = floatval($santri->kategori->tarifTerbaru->nominal);
                    
                    // Tunggakan dari pembayaran yang belum lunas
                    $tunggakanDariPembayaran = $santri->pembayaran
                        ->where('status', '!=', 'success')
                        ->where('status', '!=', 'failed')
                        ->sum('nominal');

                    $totalTunggakan += $tunggakanDariPembayaran;
                    
                    // Tunggakan dari bulan yang belum ada pembayaran
                    foreach ($periode as $p) {
                        $key = $p['bulan'] . '-' . $p['tahun'];
                        if (!in_array($key, $pembayaranLunas)) {
                        $totalTunggakan += $tarifSpp;
                        }
                    }

                    $currentMonth->addMonth();
                }
            }

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
                ->with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
                    $query->where(function($q) {
                        $q->where('status', '!=', 'success')
                          ->where('status', '!=', 'failed');
                    })->orWhere('status', 'success');
                }])
                ->get()
                ->map(function($santri) {
                    // Inisialisasi total tunggakan
                    $santri->total_tunggakan = 0;
                    $santri->jumlah_bulan_tunggakan = 0;

                    if (!$santri->kategori || !$santri->kategori->tarifTerbaru) {
                        return $santri;
                    }

                    // Konversi nominal ke float untuk perhitungan yang akurat
                    $tarifSpp = floatval($santri->kategori->tarifTerbaru->nominal);
                    
                    // Hitung periode aktif santri
                    $bulanMasuk = Carbon::parse($santri->tanggal_masuk)->startOfMonth();
                    $bulanSekarang = Carbon::now()->startOfMonth();
                    
                    // Generate periode yang harus dibayar
                    $periode = collect();
                    $currentMonth = clone $bulanMasuk;
                    while ($currentMonth <= $bulanSekarang) {
                        $periode->push([
                            'bulan' => $currentMonth->format('m'),
                            'tahun' => $currentMonth->format('Y')
                        ]);
                        $currentMonth->addMonth();
                    }
                    
                    // Pembayaran yang sudah lunas
                    $pembayaranLunas = $santri->pembayaran
                        ->where('status', 'success')
                        ->map(function($payment) {
                            return $payment->bulan . '-' . $payment->tahun;
                        })
                        ->toArray();
                    
                    // Hitung tunggakan dari pembayaran yang belum lunas
                    $tunggakanDariPembayaran = $santri->pembayaran
                        ->where('status', '!=', 'success')
                        ->where('status', '!=', 'failed')
                        ->sum('nominal');
                    
                    $santri->total_tunggakan = $tunggakanDariPembayaran;
                    
                    // Hitung tunggakan dari bulan yang belum ada pembayaran
                    foreach ($periode as $p) {
                        $key = $p['bulan'] . '-' . $p['tahun'];
                        if (!in_array($key, $pembayaranLunas)) {
                            $santri->total_tunggakan += $tarifSpp;
                            $santri->jumlah_bulan_tunggakan++;
                        }
                    }

                    return $santri;
                })
                ->filter(function($santri) {
                    return $santri->total_tunggakan > 0;
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
