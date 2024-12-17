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
                'tagihan' => collect(),
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

        // Ambil data pembayaran
        $pembayaran = PembayaranSpp::where('santri_id', $santri->id)
            ->where('status', 'success')
            ->pluck('bulan')
            ->toArray();

        // Generate tagihan untuk 12 bulan
        $tagihan = collect();
        $total_tunggakan = 0;
        $bulan_sekarang = Carbon::now()->month;
        $tahun_sekarang = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create(null, $i, 1)->format('m');
            $nama_bulan = Carbon::create(null, $i, 1)->translatedFormat('F');
            $status = in_array($bulan, $pembayaran) ? 'Lunas' : 'Belum Lunas';

            // Hitung tunggakan
            if ($status === 'Belum Lunas' && $i <= $bulan_sekarang) {
                $total_tunggakan += $santri->kategori->tarif ?? 0;
            }

            $tagihan->push([
                'bulan' => $nama_bulan,
                'tahun' => $tahun_sekarang,
                'nominal' => $santri->kategori->tarif ?? 0,
                'status' => $status,
                'status_class' => $status === 'Lunas' ? 'success' : 'warning'
            ]);
        }

        return response()
            ->view('wali.tagihan.index', compact('santri', 'santri_list', 'tagihan', 'total_tunggakan'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
