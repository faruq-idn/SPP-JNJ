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

        // Hitung total tunggakan dari semua pembayaran dengan status failed/pending/unpaid
        $totalTunggakan = PembayaranSpp::tunggakan()
            ->where('nominal', '>', 0)
            ->sum('nominal');

        // Ambil semua santri yang memiliki tunggakan
        $santri = Santri::with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
            $query->tunggakan()
                ->where('nominal', '>', 0);
        }])
        ->whereHas('pembayaran', function($q) {
            $q->tunggakan()
              ->where('nominal', '>', 0);
        })->get();

        // Hitung jumlah santri dengan tunggakan (pending/unpaid)
        $santriNunggak = Santri::where('status', 'aktif')
            ->whereHas('pembayaran', function($q) {
                $q->tunggakan()
                  ->where('nominal', '>', 0);
            })
            ->count();

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
        // Load semua santri yang memiliki tunggakan
        $query = Santri::with(['kategori.tarifTerbaru', 'wali', 'pembayaran' => function($q) {
            $q->tunggakan()
              ->where('nominal', '>', 0)
              ->orderBy('tahun')
              ->orderBy('bulan');
        }])
        ->whereHas('pembayaran', function($q) {
            $q->tunggakan()
              ->where('nominal', '>', 0);
        })
        ->when($request->filled('kategori_id'), function($q) use ($request) {
            $q->where('kategori_id', $request->kategori_id);
        });

        // Ambil semua data santri
        $santri = $query->get();

        // Filter berdasarkan request
        if ($request->filled('status')) {
            if ($request->status === 'keluar') {
                $santri = $santri->where('status', 'tidak aktif');
            } else {
                $santri = $santri->where('status', $request->status);
            }
        }

        // Filter jenjang dan kelas hanya untuk status aktif atau semua status
        if ($request->status === 'aktif' || !$request->filled('status')) {
            if ($request->filled('jenjang')) {
                $santri = $santri->where('jenjang', $request->jenjang);
            }
            if ($request->filled('kelas')) {
                $santri = $santri->where('kelas', $request->kelas);
            }
        }

        // Hitung tunggakan setiap santri
        $santri = $santri->map(function($s) {
            $tunggakan = $s->pembayaran
                ->filter(function($payment) {
                    return $payment->isTunggakan() && $payment->nominal > 0;
                });

            $s->total_tunggakan = $tunggakan->sum('nominal');
            $s->jumlah_bulan_tunggakan = $tunggakan->count();

            return $s;
        })
        ->filter(function($s) use ($request) {
            if ($request->filled('min_tunggakan')) {
                return $s->jumlah_bulan_tunggakan >= (int)$request->min_tunggakan;
            }
            return $s->jumlah_bulan_tunggakan > 0;
        })
        ->values();

        // Hitung total tunggakan dari santri yang sudah difilter
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
                         ->whereIn('status', ['pending', 'unpaid']);
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

    public function pembayaranSantriTahunanPdf(Request $request, Santri $santri, $tahun)
    {
        // Ambil pembayaran santri per tahun (hanya yang ada), urutkan per bulan
        $pembayaran = PembayaranSpp::with(['metode_pembayaran'])
            ->where('santri_id', $santri->id)
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        $totalPembayaran = $pembayaran->where('status', 'success')->sum('nominal');

        return $this->pdfExport->exportPembayaranSantriTahunan($santri, $tahun, $pembayaran, $totalPembayaran);
    }
}
