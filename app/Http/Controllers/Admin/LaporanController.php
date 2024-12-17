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

class LaporanController extends Controller
{
    public function index()
    {
        // Data untuk filter
        $tahun = range(date('Y'), date('Y')-5);
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $kategori = KategoriSantri::pluck('nama', 'id');
        $jenjang = ['SMP', 'SMA'];

        return view('admin.laporan.index', compact('tahun', 'bulan', 'kategori', 'jenjang'));
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
            });

        $pembayaran = $query->latest()->get();
        $totalPembayaran = $pembayaran->sum('nominal');

        return view('admin.laporan.pembayaran', compact('pembayaran', 'totalPembayaran'));
    }

    public function tunggakan(Request $request)
    {
        $query = Santri::with(['kategori', 'wali', 'pembayaran' => function($query) {
                $query->where('status', 'pending');
            }])
            ->when($request->filled('jenjang'), function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            })
            ->when($request->filled('kelas'), function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            })
            ->where('status', 'aktif')
            ->withCount(['pembayaran as tunggakan_count' => function($query) {
                $query->where('status', 'pending');
            }])
            ->having('tunggakan_count', '>', 0);

        $santri = $query->get();
        $totalTunggakan = $santri->sum(function($s) {
            return $s->pembayaran->sum('nominal');
        });

        return view('admin.laporan.tunggakan', compact('santri', 'totalTunggakan'));
    }

    public function exportPembayaran(Request $request)
    {
        $pembayaran = PembayaranSpp::with(['santri.kategori', 'metode_pembayaran'])
            ->when($request->filled('tanggal_awal'), function($q) use ($request) {
                $q->whereDate('tanggal_bayar', '>=', $request->tanggal_awal);
            })
            ->when($request->filled('tanggal_akhir'), function($q) use ($request) {
                $q->whereDate('tanggal_bayar', '<=', $request->tanggal_akhir);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->get()
            ->map(function($p) {
                return [
                    'Tanggal' => $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-',
                    'NISN' => $p->santri->nisn,
                    'Nama Santri' => $p->santri->nama,
                    'Kelas' => $p->santri->jenjang . ' ' . $p->santri->kelas,
                    'Bulan' => Carbon::createFromFormat('m', $p->bulan)->translatedFormat('F Y'),
                    'Nominal' => 'Rp ' . number_format($p->nominal, 0, ',', '.'),
                    'Metode' => $p->metode_pembayaran->nama ?? 'Manual',
                    'Status' => ucfirst($p->status),
                    'Keterangan' => $p->keterangan ?? '-'
                ];
            });

        return (new FastExcel($pembayaran))
            ->download('laporan-pembayaran.xlsx');
    }

    public function exportTunggakan(Request $request)
    {
        $santri = Santri::with(['kategori', 'wali', 'pembayaran' => function($query) {
                $query->where('status', 'pending');
            }])
            ->when($request->filled('jenjang'), function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            })
            ->when($request->filled('kelas'), function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            })
            ->where('status', 'aktif')
            ->withCount(['pembayaran as tunggakan_count' => function($query) {
                $query->where('status', 'pending');
            }])
            ->having('tunggakan_count', '>', 0)
            ->get()
            ->map(function($s) {
                return [
                    'NISN' => $s->nisn,
                    'Nama Santri' => $s->nama,
                    'Kelas' => $s->jenjang . ' ' . $s->kelas,
                    'Kategori' => $s->kategori->nama,
                    'Jumlah Tunggakan' => $s->tunggakan_count . ' bulan',
                    'Total Nominal' => 'Rp ' . number_format($s->pembayaran->sum('nominal'), 0, ',', '.'),
                    'Wali Santri' => $s->wali->name ?? '-'
                ];
            });

        return (new FastExcel($santri))
            ->download('laporan-tunggakan.xlsx');
    }
}
