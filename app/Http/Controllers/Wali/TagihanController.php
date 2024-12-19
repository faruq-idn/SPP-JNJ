<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua santri yang terhubung dengan wali
        $santri_list = Santri::where('wali_id', Auth::id())->get();

        if ($santri_list->isEmpty()) {
            return response()->view('wali.tagihan.index', [
                'santri' => null,
                'santri_list' => collect(),
                'pembayaranPerTahun' => [],
                'total_tunggakan' => 0
            ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        }

        // Ambil santri yang dipilih atau santri pertama sebagai default
        $santri = null;
        $selected_santri_id = Session::get('selected_santri_id');

        if ($selected_santri_id && $santri_list->contains('id', $selected_santri_id)) {
            $santri = $santri_list->find($selected_santri_id);
        } else {
            $santri = $santri_list->first();
            Session::put('selected_santri_id', $santri->id);
        }

        // Ambil tahun-tahun yang memiliki pembayaran
        $tahunPembayaran = PembayaranSpp::where('santri_id', $santri->id)
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Jika belum ada data tahun, gunakan tahun sekarang
        if (empty($tahunPembayaran)) {
            $tahunPembayaran = [date('Y')];
        }

        // Siapkan data pembayaran per tahun
        $pembayaranPerTahun = [];
        $total_tunggakan = 0;
        foreach ($tahunPembayaran as $tahun) {
            $pembayaran = [];
            // Data pembayaran yang sudah ada
            $existingPembayaran = PembayaranSpp::with('metode_pembayaran')
                ->where('santri_id', $santri->id)
                ->where('tahun', $tahun)
                ->get()
                ->keyBy('bulan');

            // Generate 12 bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $nominal = $santri->kategori->tarifTerbaru->nominal ?? 0;

                if (!isset($existingPembayaran[$bulanPadded]) &&
                    $tahun <= date('Y') &&
                    $bulan <= ($tahun == date('Y') ? date('m') : 12)) {
                    $total_tunggakan += $nominal;
                }

                $pembayaran[] = $existingPembayaran->get($bulanPadded) ?? (object)[
                    'bulan' => $bulanPadded,
                    'tahun' => $tahun,
                    'nominal' => $nominal,
                    'status' => 'pending',
                    'tanggal_bayar' => null,
                    'metode_pembayaran' => null
                ];
            }

            $pembayaranPerTahun[$tahun] = $pembayaran;
        }

        return response()
            ->view('wali.tagihan.index', compact('santri', 'santri_list', 'pembayaranPerTahun', 'total_tunggakan'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
