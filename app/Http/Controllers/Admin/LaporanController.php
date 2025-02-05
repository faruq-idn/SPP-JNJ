<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use App\Models\KategoriSantri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanController extends Controller
{
    public function index()
    {
        $bulanIni = now()->format('m');
        $tahunIni = now()->format('Y');

        // Data untuk rangkuman
        $totalPembayaranBulanIni = PembayaranSpp::where('status', 'success')
            ->whereYear('tanggal_bayar', $tahunIni)
            ->whereMonth('tanggal_bayar', $bulanIni)
            ->sum('nominal');

        $santriLunas = Santri::whereHas('pembayaran', function($query) use ($bulanIni, $tahunIni) {
            $query->where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->where('status', 'success');
        })->count();

        $totalTunggakan = PembayaranSpp::whereIn('status', ['unpaid', 'pending'])
            ->sum('nominal');

        $santriNunggak = Santri::whereHas('pembayaran', function($query) {
            $query->whereIn('status', ['unpaid', 'pending']);
        })->count();

        // Data untuk chart pembayaran
        $chartData = $this->getChartData();

        // Data untuk chart kategori
        $chartKategori = $this->getChartKategori();

        // Data untuk filter
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

        // Fill missing months with 0
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
            'data' => $data->map(function($item) {
                $total = $item->lunas + $item->belum_lunas;
                return $total > 0 ? round(($item->lunas / $total) * 100, 2) : 0;
            })
        ];
    }

    public function pembayaran(Request $request)
    {
        $query = PembayaranSpp::with(['santri.kategori', 'metode_pembayaran'])
            ->when($request->filled('tanggal_awal'), function($q) use ($request) {
                $q->whereDate('tanggal_bayar', '>=', $request->tanggal_awal);
            })
            ->when($request->filled('tanggal_akhir'), function($q) use ($request) {
                $q->whereDate('tanggal_bayar', '<=', $request->tanggal_akhir);
            })
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
            if ($request->export === 'pdf') {
                $pdf = PDF::loadView('admin.laporan.pdf.pembayaran', compact('pembayaran', 'totalPembayaran'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download('laporan-pembayaran.pdf');
            } else {
                return $this->exportPembayaranExcel($pembayaran);
            }
        }

        return view('admin.laporan.pembayaran', compact('pembayaran', 'totalPembayaran'));
    }

    public function tunggakan(Request $request)
    {
        $query = Santri::with(['kategori', 'wali', 'pembayaran' => function($query) {
            $query->whereIn('status', ['unpaid', 'pending']);
        }])
        ->when($request->filled('jenjang'), function($q) use ($request) {
            $q->where('jenjang', $request->jenjang);
        })
        ->when($request->filled('kelas'), function($q) use ($request) {
            $q->where('kelas', $request->kelas);
        })
        ->when($request->filled('kategori_id'), function($q) use ($request) {
            $q->where('kategori_id', $request->kategori_id);
        })
        ->where('status', 'aktif')
        ->withCount(['pembayaran as tunggakan_count' => function($query) use ($request) {
            $query->whereIn('status', ['unpaid', 'pending'])
                ->when($request->filled('min_tunggakan'), function($q) use ($request) {
                    $q->having('tunggakan_count', '>=', $request->min_tunggakan);
                });
        }])
        ->having('tunggakan_count', '>', 0);

        $santri = $query->get();
        $totalTunggakan = $santri->sum(function($s) {
            return $s->pembayaran->sum('nominal');
        });

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                $pdf = PDF::loadView('admin.laporan.pdf.tunggakan', compact('santri', 'totalTunggakan'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download('laporan-tunggakan.pdf');
            } else {
                return $this->exportTunggakanExcel($santri);
            }
        }

        return view('admin.laporan.tunggakan', compact('santri', 'totalTunggakan'));
    }

    private function exportPembayaranExcel($pembayaran)
    {
        $data = $pembayaran->map(function($p) {
            return [
                'Tanggal' => $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-',
                'NISN' => $p->santri->nisn,
                'Nama Santri' => $p->santri->nama,
                'Kelas' => $p->santri->jenjang . ' ' . $p->santri->kelas,
                'Kategori' => $p->santri->kategori->nama,
                'Bulan' => Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F'),
                'Tahun' => $p->tahun,
                'Nominal' => 'Rp ' . number_format($p->nominal, 0, ',', '.'),
                'Metode' => $p->metode_pembayaran->nama ?? 'Manual',
                'Status' => ucfirst($p->status),
                'Keterangan' => $p->keterangan ?? '-'
            ];
        });

        return (new FastExcel($data))->download('laporan-pembayaran.xlsx');
    }

    private function exportTunggakanExcel($santri)
    {
        $data = $santri->map(function($s) {
            return [
                'NISN' => $s->nisn,
                'Nama Santri' => $s->nama,
                'Kelas' => $s->jenjang . ' ' . $s->kelas,
                'Kategori' => $s->kategori->nama,
                'Wali Santri' => $s->wali->name ?? '-',
                'No HP Wali' => $s->wali->no_hp ?? '-',
                'Jumlah Tunggakan' => $s->tunggakan_count . ' bulan',
                'Total Nominal' => 'Rp ' . number_format($s->pembayaran->sum('nominal'), 0, ',', '.'),
                'Status' => implode(', ', $s->pembayaran->pluck('bulan')->map(function($bulan) {
                    return Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
                })->toArray())
            ];
        });

        return (new FastExcel($data))->download('laporan-tunggakan.xlsx');
    }
}
