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
    public function index(Request $request)
    {
        try {
            // Ambil semua santri yang terhubung dengan wali
            $santri_list = Santri::where('wali_id', Auth::id())->get();

            if ($santri_list->isEmpty()) {
                return response()->view('wali.dashboard', [
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
            $pembayaran = PembayaranSpp::where('santri_id', $santri->id)
                ->latest()
                ->take(5)
                ->get();

            return response()
                ->view('wali.dashboard', compact('santri', 'santri_list', 'pembayaran'))
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        } catch (\Exception $e) {
            Log::error('Error in WaliDashboard: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }

    public function changeSantri(Request $request)
    {
        $santri_id = $request->santri_id;

        // Validasi santri_id
        $santri = Santri::where('wali_id', Auth::id())
            ->where('id', $santri_id)
            ->first();

        if ($santri) {
            Session::put('selected_santri_id', $santri_id);
            return back()->with('success', 'Berhasil mengubah santri');
        }

        return back()->with('error', 'Santri tidak ditemukan');
    }
}
