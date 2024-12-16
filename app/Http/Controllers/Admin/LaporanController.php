<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use App\Models\KategoriSantri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = PembayaranSpp::with(['santri.kategori', 'santri.wali'])
            ->select('pembayaran_spp.*')
            ->join('santri', 'santri.id', '=', 'pembayaran_spp.santri_id');

        // Filter berdasarkan tahun
        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan bulan
        if ($request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori santri
        if ($request->kategori_id) {
            $query->where('santri.kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenjang
        if ($request->jenjang) {
            $query->where('santri.jenjang', $request->jenjang);
        }

        // Filter berdasarkan kelas
        if ($request->kelas) {
            $query->where('santri.kelas', $request->kelas);
        }

        $pembayaran = $query->orderBy('tanggal_bayar', 'desc')->get();

        // Hitung total
        $total = [
            'nominal' => $pembayaran->sum('nominal'),
            'lunas' => $pembayaran->where('status', 'success')->count(),
            'tunggakan' => $pembayaran->where('status', 'pending')->count(),
        ];

        return response()->json([
            'data' => $pembayaran->map(function($p) {
                return [
                    'tanggal' => $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-',
                    'nama_santri' => $p->santri->nama,
                    'nisn' => $p->santri->nisn,
                    'kelas' => $p->santri->jenjang . ' ' . $p->santri->kelas,
                    'kategori' => $p->santri->kategori->nama,
                    'wali' => $p->santri->wali->name,
                    'nominal' => $p->nominal,
                    'bulan' => $p->bulan,
                    'tahun' => $p->tahun,
                    'status' => $p->status,
                    'metode' => $p->metode_pembayaran
                ];
            }),
            'total' => $total
        ]);
    }

    public function tunggakan(Request $request)
    {
        $query = Santri::with(['kategori', 'wali', 'pembayaran' => function($q) {
            $q->whereYear('created_at', date('Y'))
              ->orderBy('bulan', 'desc');
        }]);

        // Filter berdasarkan kategori
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenjang
        if ($request->jenjang) {
            $query->where('jenjang', $request->jenjang);
        }

        // Filter berdasarkan kelas
        if ($request->kelas) {
            $query->where('kelas', $request->kelas);
        }

        $santri = $query->get();

        $data = $santri->map(function($s) {
            $tunggakan = $s->pembayaran->where('status', 'pending')->count();
            $nominal = $s->kategori->tarifTerbaru->nominal ?? 0;

            return [
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'kelas' => $s->jenjang . ' ' . $s->kelas,
                'kategori' => $s->kategori->nama,
                'wali' => $s->wali->name,
                'jumlah_tunggakan' => $tunggakan,
                'total_tunggakan' => $tunggakan * $nominal
            ];
        })->filter(function($item) {
            return $item['jumlah_tunggakan'] > 0;
        })->values();

        $total = [
            'santri' => $data->count(),
            'tunggakan' => $data->sum('jumlah_tunggakan'),
            'nominal' => $data->sum('total_tunggakan')
        ];

        return response()->json([
            'data' => $data,
            'total' => $total
        ]);
    }

    public function exportPembayaran(Request $request)
    {
        // Logic untuk export ke Excel
    }

    public function exportTunggakan(Request $request)
    {
        // Logic untuk export ke Excel
    }
}
