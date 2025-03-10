<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('Accessing wali dashboard', [
            'wali_id' => Auth::id(),
            'timestamp' => now()
        ]);

        // Ambil semua santri yang terhubung dengan wali
        $santri_list = Santri::with(['kategori.tarifTerbaru', 'riwayatKenaikanKelas'])
            ->where('wali_id', Auth::id())
            ->get();

        // Ambil santri yang belum terhubung tapi memiliki nama wali yang sama
        $unlinked_santri = Santri::with(['kategori.tarifTerbaru', 'riwayatKenaikanKelas'])
            ->whereNull('wali_id')
            ->where('nama_wali', Auth::user()->name)
            ->get();

        // Ambil santri yang belum terhubung dan belum ada nama wali
        $available_santri = Santri::with(['kategori.tarifTerbaru', 'riwayatKenaikanKelas'])
            ->whereNull('wali_id')
            ->whereNull('nama_wali')
            ->get();

        if ($santri_list->isEmpty()) {
            Log::info('No santri found for wali', ['wali_id' => Auth::id()]);
            return view('wali.dashboard', [
                'santri' => null,
                'santri_list' => $santri_list,
                'unlinked_santri' => collect(),
                'available_santri' => $available_santri,
                'pembayaran_terbaru' => collect(),
                'total_tunggakan' => 0
            ]);
        }

        // Ambil santri yang dipilih atau santri pertama sebagai default
        $santri = null;
        $selected_santri_id = Session::get('selected_santri_id');

        if ($selected_santri_id && $santri_list->contains('id', $selected_santri_id)) {
            $santri = $santri_list->find($selected_santri_id);
        } else {
            $santri = $santri_list->first();
            if ($santri) {
                Session::put('selected_santri_id', $santri->id);
            }
        }

        // Ambil data pembayaran jika ada santri yang terhubung
        $pembayaran_terbaru = collect();
        if ($santri) {
            Log::info('Processing dashboard data for santri', [
                'santri_id' => $santri->id,
                'nama' => $santri->nama,
                'tanggal_masuk' => $santri->tanggal_masuk
            ]);

            // Ambil semua pembayaran yang ada dan grouping per tahun
            $existingPembayaran = PembayaranSpp::where('santri_id', $santri->id)->get();
            $pembayaranPerTahun = $existingPembayaran->groupBy('tahun');
            
            // Hitung tunggakan
            $total_tunggakan = 0;
            $totalTunggakanPerTahun = [];
            $tunggakanBulanan = collect();

            foreach ($existingPembayaran as $pembayaran) {
                if ($pembayaran->status !== PembayaranSpp::STATUS_SUCCESS) {
                    $tahun = $pembayaran->tahun;
                    $nominal = $pembayaran->nominal;

                    // Update tunggakan
                    $total_tunggakan += $nominal;
                    
                    if (!isset($totalTunggakanPerTahun[$tahun])) {
                        $totalTunggakanPerTahun[$tahun] = 0;
                    }
                    $totalTunggakanPerTahun[$tahun] += $nominal;

                    // Tambahkan ke koleksi tunggakan bulanan
                    $tunggakanBulanan->push($pembayaran);
                }
            }

            // Urutkan tunggakan dari yang terbaru dan ambil 5
            $pembayaran_terbaru = $tunggakanBulanan
                ->sortByDesc(function($pembayaran) {
                    return sprintf('%d%02d', $pembayaran->tahun, $pembayaran->bulan);
                })
                ->take(5)
                ->values();

            Log::info('Dashboard data processed successfully', [
                'santri_id' => $santri->id,
                'total_tunggakan' => $total_tunggakan,
                'jumlah_tunggakan_bulanan' => $tunggakanBulanan->count(),
                'tunggakan_terbaru' => $pembayaran_terbaru->count()
            ]);
        }

        return view('wali.dashboard', compact(
            'santri',
            'santri_list',
            'unlinked_santri',
            'available_santri',
            'pembayaran_terbaru',
            'total_tunggakan',
            'pembayaranPerTahun',
            'totalTunggakanPerTahun'
        ));
    }

    public function changeSantri(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id'
        ]);

        $santri_id = $request->santri_id;

        // Validasi santri_id
        $santri = Santri::where('wali_id', Auth::id())
            ->where('id', $santri_id)
            ->first();

        if (!$santri) {
            return back()->with('error', 'Santri tidak ditemukan atau bukan santri Anda');
        }

        // Simpan santri_id yang dipilih ke session
        Session::put('selected_santri_id', $santri_id);

        // Kembali ke halaman sebelumnya
        return back();
    }

    public function hubungkan()
    {
        // Ambil santri yang belum terhubung tapi memiliki nama wali yang sama
        $unlinked_santri = Santri::whereNull('wali_id')
            ->where('nama_wali', Auth::user()->name)
            ->get();

        // Ambil santri yang belum terhubung dan belum ada nama wali
        $available_santri = Santri::whereNull('wali_id')
            ->whereNull('nama_wali')
            ->get();

        return view('wali.hubungkan', compact('unlinked_santri', 'available_santri'));
    }

    public function getData()
    {
        try {
            $data = [
                'status' => 'success',
                'data' => [
                    // data yang diperlukan
                ]
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Error in getData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }
}
