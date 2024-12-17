<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PembayaranController extends Controller
{
    public function riwayat(Request $request)
    {
        // Ambil semua santri yang terhubung dengan wali
        $santri_list = Santri::where('wali_id', Auth::id())->get();

        if ($santri_list->isEmpty()) {
            return response()->view('wali.pembayaran.riwayat', [
                'santri' => null,
                'santri_list' => collect(),
                'pembayaran' => collect()
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
        $pembayaran = PembayaranSpp::with('metode_pembayaran')
            ->select('id', 'santri_id', 'tanggal_bayar', 'bulan', 'nominal', 'metode_pembayaran_id', 'status', 'created_at')
            ->where('santri_id', $santri->id)
            ->latest()
            ->paginate(10);

        return response()
            ->view('wali.pembayaran.riwayat', compact('santri', 'santri_list', 'pembayaran'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
