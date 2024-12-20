<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TagihanController extends Controller
{
    public function index()
    {
        // Ambil santri yang sedang dipilih
        $selected_santri_id = Session::get('selected_santri_id');

        // Ambil daftar santri yang terhubung dengan wali
        $santri_list = Santri::where('wali_id', Auth::id())
            ->select('id', 'nama', 'nisn', 'kelas', 'kategori_id', 'status_spp')
            ->with(['kategori:id,nama', 'kategori.tarifTerbaru:id,kategori_id,nominal,berlaku_mulai'])
            ->get();

        // Jika tidak ada santri yang dipilih, gunakan santri pertama
        if (!$selected_santri_id && $santri_list->isNotEmpty()) {
            $selected_santri_id = $santri_list->first()->id;
            Session::put('selected_santri_id', $selected_santri_id);
        }

        // Ambil data santri yang dipilih
        $santri = $santri_list->where('id', $selected_santri_id)->first();

        if (!$santri) {
            return redirect()->route('wali.hubungkan')
                ->with('error', 'Silakan hubungkan santri terlebih dahulu');
        }

        // Ambil pembayaran dan kelompokkan per tahun
        $pembayaranPerTahun = PembayaranSpp::where('santri_id', $santri->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'asc')
            ->get()
            ->groupBy('tahun');

        // Hitung total tunggakan
        $total_tunggakan = PembayaranSpp::where('santri_id', $santri->id)
            ->where('status', 'unpaid')
            ->sum('nominal');

        return response()
            ->view('wali.tagihan.index', compact(
                'santri',
                'santri_list',
                'pembayaranPerTahun',
                'total_tunggakan'
            ))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
