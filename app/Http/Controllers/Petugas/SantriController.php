<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\MetodePembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::with(['kategori', 'wali'])
            ->orderBy('nama')
            ->get();
            
        return view('petugas.santri.index', compact('santri'));
    }

    public function show(Santri $santri)
    {
        // Load necessary relationships, including wali's no_hp
        $santri->load(['kategori.tarifTerbaru', 'wali']);
        
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');

        $pembayaranPerTahun = [];
        $totalTunggakanPerTahun = [];
        
        // Get latest tarif from kategori
        $latestTarif = $santri->kategori->tarifTerbaru;
        $nominal = $latestTarif ? $latestTarif->nominal : 0;
        
        // Ambil semua tahun yang memiliki tagihan
        $tahunTagihan = $santri->pembayaran()
            ->select(DB::raw('DISTINCT tahun'))
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        // Tambahkan tahun sekarang jika belum ada
        if (!in_array($tahunSekarang, $tahunTagihan)) {
            array_unshift($tahunTagihan, $tahunSekarang);
        }

        // Proses setiap tahun
        foreach ($tahunTagihan as $tahun) {
            $pembayaranList = [];
            $tunggakanTahun = 0;
            
            // Tentukan bulan awal berdasarkan tanggal masuk santri
            $bulanAwal = 1;
            $tanggalMasuk = Carbon::parse($santri->tanggal_masuk);
            if ($tahun == $tanggalMasuk->year) {
                $bulanAwal = $tanggalMasuk->month;
            } elseif ($tahun < $tanggalMasuk->year) {
                continue; // Skip tahun sebelum santri masuk
            }

            // Ambil semua tagihan yang ada untuk tahun ini
            $tagihanTahunIni = $santri->pembayaran()
                ->where('tahun', $tahun)
                ->get()
                ->keyBy('bulan');

            // Hanya tampilkan bulan yang sudah ada tagihannya
            foreach ($tagihanTahunIni as $pembayaran) {
                if ($pembayaran->status !== 'success') {
                    $tunggakanTahun += $pembayaran->nominal;
                }
                $pembayaranList[] = $pembayaran;
            }
            
            if (!empty($pembayaranList)) {
                $pembayaranPerTahun[$tahun] = collect($pembayaranList)->sortByDesc('bulan');
                $totalTunggakanPerTahun[$tahun] = $tunggakanTahun;
            }
        }

        // Hitung total tunggakan keseluruhan
        $totalTunggakan = array_sum($totalTunggakanPerTahun);

        // Hitung status SPP
        $statusSpp = $this->hitungStatusSpp($santri, $tahunSekarang);

        $metode = MetodePembayaran::all();

        return view('petugas.santri.show', compact(
            'santri',
            'pembayaranPerTahun',
            'totalTunggakan',
            'totalTunggakanPerTahun',
            'statusSpp',
            'metode'
        ));
    }

    private function hitungStatusSpp($santri, $tahun)
    {
        $pembayaranTahunIni = $santri->pembayaran()
            ->where('tahun', $tahun)
            ->whereMonth('created_at', '<=', now()->month)
            ->get();

        if ($pembayaranTahunIni->isEmpty()) {
            return 'Belum Lunas';
        }

        return $pembayaranTahunIni->every(function ($pembayaran) {
            return $pembayaran->status === 'success';
        }) ? 'Lunas' : 'Belum Lunas';
    }

    public function kelas($jenjang, $kelas)
    {
        // Validasi format jenjang dan kelas
        if (!in_array(strtoupper($jenjang), ['SMP', 'SMA'])) {
            abort(404);
        }

        $validKelas = $jenjang == 'smp' 
            ? ['7A', '7B', '8A', '8B', '9A', '9B']
            : ['10A', '10B', '11A', '11B', '12A', '12B'];

        if (!in_array(strtoupper($kelas), $validKelas)) {
            abort(404);
        }

        $santri = Santri::with(['kategori', 'wali'])
            ->where('jenjang', strtoupper($jenjang))
            ->where('kelas', strtoupper($kelas))
            ->where('status', 'aktif')
            ->get();

        $currentKelas = [
            'jenjang' => strtoupper($jenjang),
            'kelas' => strtoupper($kelas)
        ];

        return view('petugas.santri.index', compact('santri', 'currentKelas'));
    }
}
