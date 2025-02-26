<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use App\Models\KategoriSantri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\Exports\ExcelExportController;
use App\Http\Controllers\Admin\Exports\PdfExportController;

class LaporanController extends Controller
{
    protected $excelExport;
    protected $pdfExport;

    public function __construct(ExcelExportController $excelExport, PdfExportController $pdfExport)
    {
        $this->excelExport = $excelExport;
        $this->pdfExport = $pdfExport;
    }

    public function index()
    {
        $bulanIni = now()->format('m');
        $tahunIni = now()->format('Y');

        $totalPembayaranBulanIni = PembayaranSpp::where('status', 'success')
            ->whereYear('tanggal_bayar', $tahunIni)
            ->whereMonth('tanggal_bayar', $bulanIni)
            ->sum('nominal');

        $santriLunas = Santri::whereHas('pembayaran', function($query) use ($bulanIni, $tahunIni) {
            $query->where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->where('status', 'success');
        })->count();

        $totalTunggakan = 0;
        $santri = Santri::with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
<<<<<<< HEAD
            $query->whereIn('status', ['failed', 'pending', 'unpaid'])
                ->where('nominal', '>', 0)
                ->orderBy('tahun')
                ->orderBy('bulan');
        }])->get();

        foreach ($santri as $s) {
            if ($s->kategori && $s->kategori->tarifTerbaru) {
                $pembayaranBelumLunas = $s->pembayaran
                    ->whereIn('status', ['failed', 'pending', 'unpaid'])
                    ->where('nominal', '>', 0);
                $totalTunggakan += $pembayaranBelumLunas->sum('nominal');
            }
        }

=======
                $query->where(function($q) {
                    $q->where('status', '!=', 'success')
                      ->where('status', '!=', 'failed');
                })->orWhere('status', 'success');
            }])
            ->get();

        foreach ($santri as $s) {
            if ($s->kategori && $s->kategori->tarifTerbaru) {
                $tarifSpp = floatval($s->kategori->tarifTerbaru->nominal);
                
                // Hitung periode aktif
                $bulanMasuk = Carbon::parse($s->tanggal_masuk)->startOfMonth();
                $bulanSekarang = Carbon::now()->startOfMonth();
                
                // Pembayaran lunas
                $pembayaranLunas = $s->pembayaran
                    ->where('status', 'success')
                    ->map(function($payment) {
                        return $payment->bulan . '-' . $payment->tahun;
                    })
                    ->toArray();
                
                // Tunggakan dari pembayaran belum lunas
                $totalTunggakan += $s->pembayaran
                    ->where('status', '!=', 'success')
                    ->where('status', '!=', 'failed')
                    ->sum('nominal');
                
                // Generate periode & hitung tunggakan
                $currentMonth = clone $bulanMasuk;
                while ($currentMonth <= $bulanSekarang) {
                    $key = $currentMonth->format('m') . '-' . $currentMonth->format('Y');
                    if (!in_array($key, $pembayaranLunas)) {
                        $totalTunggakan += $tarifSpp;
                    }
                    $currentMonth->addMonth();
                }
            }
        }

        // Hanya hitung santri aktif yang nunggak untuk ditampilkan di dashboard
>>>>>>> 8e35553 (fix: update HTML doctype declaration, modify Midtrans notification URL, and enhance Kenaikan Kelas history retrieval; improve DataTable initialization and dashboard display)
        $santriNunggak = $santri->where('status', 'aktif')->filter(function($santri) {
            if (!$santri->kategori || !$santri->kategori->tarifTerbaru) {
                return false;
            }

<<<<<<< HEAD
            return $santri->pembayaran
                ->whereIn('status', ['failed', 'pending', 'unpaid'])
                ->where('nominal', '>', 0)
                ->count() > 0;
=======
            // Periksa pembayaran belum lunas (pending/unpaid)
            $pembayaranBelumLunas = $santri->pembayaran
                ->where('status', '!=', 'success')
                ->where('status', '!=', 'failed')
                ->count();

            // Hitung periode yang harus dibayar
            $bulanMasuk = Carbon::parse($santri->tanggal_masuk)->startOfMonth();
            $bulanSekarang = Carbon::now()->startOfMonth();

            // Pembayaran yang sudah lunas
            $pembayaranLunas = $santri->pembayaran
                ->where('status', 'success')
                ->map(function($payment) {
                    return $payment->bulan . '-' . $payment->tahun;
                })
                ->toArray();

            // Periksa semua periode dari tanggal masuk sampai sekarang
            $currentMonth = clone $bulanMasuk;
            while ($currentMonth <= $bulanSekarang) {
                $key = $currentMonth->format('m') . '-' . $currentMonth->format('Y');
                if (!in_array($key, $pembayaranLunas)) {
                    return true; // Ada tunggakan
                }
                $currentMonth->addMonth();
            }

            return $pembayaranBelumLunas > 0;
>>>>>>> 8e35553 (fix: update HTML doctype declaration, modify Midtrans notification URL, and enhance Kenaikan Kelas history retrieval; improve DataTable initialization and dashboard display)
        })->count();

        $chartData = $this->getChartData();
        $chartKategori = $this->getChartKategori();
        $jenjang = ['SMP', 'SMA'];
        $kategori = KategoriSantri::pluck('nama', 'id');

        return view('admin.laporan.index', compact(
            'totalPembayaranBulanIni',
            'santriLunas',
            'totalTunggakan',
            'santriNunggak',
            'chartData',
            'chartKategori',
            'jenjang',
            'kategori'
        ));
    }

    public function pembayaran(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $kategori_list = KategoriSantri::all();

        $query = PembayaranSpp::with(['santri.kategori', 'metode_pembayaran'])
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->when($request->filled('status'), function($q) use ($request) {
                $q->whereHas('santri', function($query) use ($request) {
                    $query->where('status', $request->status);
                });
            })
            ->when($request->filled('kategori'), function($q) use ($request) {
                $q->whereHas('santri', function($query) use ($request) {
                    $query->where('kategori_id', $request->kategori);
                });
            })
            ->when($request->filled('jenjang'), function($q) use ($request) {
                $q->whereHas('santri', function($query) use ($request) {
                    $query->where('jenjang', $request->jenjang);
                });
            })
            ->when($request->filled('kelas'), function($q) use ($request) {
                $q->whereHas('santri', function($query) use ($request) {
                    $query->where('kelas', $request->kelas);
                });
            });

        $pembayaran = $query->latest()->get();
        $totalPembayaran = $pembayaran->sum('nominal');

        if ($request->has('export')) {
            if ($request->export === 'excel') {
                return $this->excelExport->exportPembayaran($pembayaran, $bulan, $tahun);
            } elseif ($request->export === 'pdf') {
                return $this->pdfExport->exportPembayaran($pembayaran, $bulan, $tahun);
            }
        }

        return view('admin.laporan.pembayaran', compact('pembayaran', 'totalPembayaran', 'bulan', 'tahun', 'kategori_list'));
    }

    public function tunggakan(Request $request)
    {
        // Load santri dengan semua pembayaran yang ada
        $query = Santri::with(['kategori.tarifTerbaru', 'wali', 'pembayaran' => function($q) {
                // Load all payments with status failed, pending, or unpaid
                $q->whereIn('status', ['failed', 'pending', 'unpaid'])
                  ->where('nominal', '>', 0)
                  ->orderBy('tahun')
                  ->orderBy('bulan');
            }])
            // Filter santri that have unpaid payments
            ->whereHas('pembayaran', function($q) {
                $q->whereIn('status', ['failed', 'pending', 'unpaid'])
                  ->where('nominal', '>', 0);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('jenjang') && (!$request->filled('status') || $request->status == 'aktif'), function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            })
            ->when($request->filled('kelas') && (!$request->filled('status') || $request->status == 'aktif'), function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            })
            ->when($request->filled('kategori_id'), function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });

        $santri = $query->get()
            ->map(function($santri) {
                $santri->total_tunggakan = 0;
                $santri->jumlah_bulan_tunggakan = 0;

                if (!$santri->kategori || !$santri->kategori->tarifTerbaru) {
                    return $santri;
                }

                // Get all failed, pending, or unpaid payments
                $pembayaranBelumLunas = $santri->pembayaran
                    ->whereIn('status', ['failed', 'pending', 'unpaid'])
                    ->where('nominal', '>', 0);

                $santri->total_tunggakan = $pembayaranBelumLunas->sum('nominal');
                $santri->jumlah_bulan_tunggakan = $pembayaranBelumLunas->count();

                return $santri;
            })
            ->filter(function($santri) use ($request) {
                if ($request->filled('min_tunggakan')) {
                    return $santri->jumlah_bulan_tunggakan >= (int)$request->min_tunggakan;
                }
                return $santri->jumlah_bulan_tunggakan > 0;
            })
            ->values();

        $totalTunggakan = $santri->sum('total_tunggakan');

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->pdfExport->exportTunggakan($santri, $totalTunggakan);
            } else {
                return $this->excelExport->exportTunggakan($santri, $totalTunggakan);
            }
        }

        return view('admin.laporan.tunggakan', compact('santri', 'totalTunggakan'));
    }

    private function getChartData()
    {
        $months = collect(range(1, 12))->map(function($month) {
            return Carbon::create(null, $month, 1)->translatedFormat('F');
        });

        $totals = DB::table('pembayaran_spp')
            ->select(DB::raw('EXTRACT(MONTH FROM tanggal_bayar) as bulan'), DB::raw('SUM(nominal) as total'))
            ->whereYear('tanggal_bayar', now()->year)
            ->where('status', 'success')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_bayar)'))
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan')
            ->map(function($total) {
                return (int) $total;
            });

        $totals = collect(range(1, 12))->mapWithKeys(function($month) use ($totals) {
            return [$month => $totals->get($month, 0)];
        });

        return [
            'labels' => $months->values()->all(),
            'data' => $totals->values()->all()
        ];
    }

    private function getChartKategori()
    {
        $bulanIni = now()->format('m');
        $tahunIni = now()->format('Y');

        $data = KategoriSantri::withCount(['santri as lunas' => function($query) use ($bulanIni, $tahunIni) {
            $query->whereHas('pembayaran', function($q) use ($bulanIni, $tahunIni) {
                $q->where('status', 'success')
                  ->where('bulan', $bulanIni)
                  ->where('tahun', $tahunIni);
            });
        }])
        ->withCount(['santri as belum_lunas' => function($query) use ($bulanIni, $tahunIni) {
            $query->where(function($q) use ($bulanIni, $tahunIni) {
                // Santri dengan pembayaran pending/unpaid
                $q->whereHas('pembayaran', function($subq) use ($bulanIni, $tahunIni) {
                    $subq->where('bulan', $bulanIni)
                         ->where('tahun', $tahunIni)
<<<<<<< HEAD
                         ->whereIn('status', ['pending', 'unpaid']);
=======
                         ->where('status', '!=', 'success')
                         ->where('status', '!=', 'failed');
>>>>>>> 8e35553 (fix: update HTML doctype declaration, modify Midtrans notification URL, and enhance Kenaikan Kelas history retrieval; improve DataTable initialization and dashboard display)
                })
                // Atau santri yang belum memiliki pembayaran untuk bulan ini
                ->orWhereDoesntHave('pembayaran', function($subq) use ($bulanIni, $tahunIni) {
                    $subq->where('bulan', $bulanIni)
                         ->where('tahun', $tahunIni)
                         ->where('status', 'success');
                });
            })->where('status', 'aktif');
        }])
        ->get();

        return [
            'labels' => $data->pluck('nama'),
            'data' => [
                'lunas' => $data->pluck('lunas'),
                'belum_lunas' => $data->pluck('belum_lunas')
            ]
        ];
    }
}
