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

        // Hitung total tunggakan dengan logika yang sama seperti di dashboard
        $totalTunggakan = 0;
        $santriAktif = Santri::where('status', 'aktif')
            ->with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
                $query->where(function($q) {
                    $q->where('status', '!=', 'success')
                      ->where('status', '!=', 'failed');
                })->orWhere('status', 'success');
            }])
            ->get();

        foreach ($santriAktif as $santri) {
            if ($santri->kategori && $santri->kategori->tarifTerbaru) {
                $tarifSpp = floatval($santri->kategori->tarifTerbaru->nominal);
                
                // Hitung periode aktif
                $bulanMasuk = Carbon::parse($santri->tanggal_masuk)->startOfMonth();
                $bulanSekarang = Carbon::now()->startOfMonth();
                
                // Pembayaran lunas
                $pembayaranLunas = $santri->pembayaran
                    ->where('status', 'success')
                    ->map(function($payment) {
                        return $payment->bulan . '-' . $payment->tahun;
                    })
                    ->toArray();
                
                // Tunggakan dari pembayaran belum lunas
                $totalTunggakan += $santri->pembayaran
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

        $santriNunggak = $santriAktif->filter(function($santri) {
            return $santri->pembayaran->where('status', '!=', 'success')->count() > 0 ||
                   !$santri->pembayaran->where('status', 'success')->count();
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
                $q->where('status', $request->status);
            })
            ->when($request->filled('kategori_id'), function($q) use ($request) {
                $q->whereHas('santri', function($query) use ($request) {
                    $query->where('kategori_id', $request->kategori_id);
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
        $query = Santri::with(['kategori.tarifTerbaru', 'wali', 'pembayaran' => function($query) {
                $query->where(function($q) {
                    $q->where('status', '!=', 'success')
                      ->where('status', '!=', 'failed');
                })->orWhere('status', 'success');
            }])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            }, function($q) {
                // Default menampilkan santri aktif
                $q->where('status', 'aktif');
            })
            ->when($request->status != 'lulus', function($q) use ($request) {
                $q->when($request->filled('jenjang'), function($subq) use ($request) {
                    $subq->where('jenjang', $request->jenjang);
                })
                ->when($request->filled('kelas'), function($subq) use ($request) {
                    $subq->where('kelas', $request->kelas);
                });
            })
            ->when($request->filled('kategori_id'), function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });

        $santri = $query->get()
            ->map(function($santri) {
                // Inisialisasi properti tunggakan
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
                $currentMonth = clone $bulanMasuk;
                $periode = collect();
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
            ->filter(function($santri) use ($request) {
                if ($request->filled('min_tunggakan')) {
                    return $santri->jumlah_bulan_tunggakan >= (int)$request->min_tunggakan;
                }
                return $santri->total_tunggakan > 0;
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
        $data = KategoriSantri::withCount(['santri as lunas' => function($query) {
            $query->whereHas('pembayaran', function($q) {
                $q->where('status', 'success')
                    ->whereYear('tanggal_bayar', now()->year)
                    ->whereMonth('tanggal_bayar', now()->month);
            });
        }])
        ->withCount(['santri as belum_lunas' => function($query) {
            $query->whereHas('pembayaran', function($q) {
                $q->whereIn('status', ['unpaid', 'pending'])
                    ->whereYear('tanggal_bayar', now()->year)
                    ->whereMonth('tanggal_bayar', now()->month);
            });
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
