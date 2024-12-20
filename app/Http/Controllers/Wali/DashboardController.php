<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua santri yang terhubung dengan wali
        $santri_list = Santri::where('wali_id', Auth::id())->get();

        // Ambil santri yang belum terhubung tapi memiliki nama wali yang sama
        $unlinked_santri = Santri::whereNull('wali_id')
            ->where('nama_wali', Auth::user()->name)
            ->get();

        // Ambil santri yang belum terhubung dan belum ada nama wali
        $available_santri = Santri::whereNull('wali_id')
            ->whereNull('nama_wali')
            ->get();

        if ($santri_list->isEmpty() && $unlinked_santri->isEmpty()) {
            return view('wali.dashboard', [
                'santri' => null,
                'santri_list' => collect(),
                'unlinked_santri' => collect(),
                'available_santri' => $available_santri,
                'pembayaran' => collect()
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
        $pembayaran = collect();
        if ($santri) {
            $pembayaran = PembayaranSpp::where('santri_id', $santri->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('wali.dashboard', compact(
            'santri',
            'santri_list',
            'unlinked_santri',
            'available_santri',
            'pembayaran'
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

        return back()->with('success', 'Berhasil mengubah santri yang dipilih');
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
}
