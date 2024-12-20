<?php

namespace App\Http\Controllers\Wali;

use App\Models\PembayaranSpp;
use App\Models\Santri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WaliDashboard extends Controller
{
    public function index()
    {
        // Ambil semua santri yang dimiliki wali
        $santri_list = Auth::user()->santri;

        // Ambil santri aktif dari session atau ambil yang pertama
        $santri = null;
        if (session()->has('santri_aktif')) {
            $santri = $santri_list->where('id', session('santri_aktif'))->first();
        }

        if (!$santri) {
            $santri = $santri_list->first();
        }

        if (!$santri) {
            return redirect()->route('wali.hubungkan')
                ->with('error', 'Silakan hubungkan data santri terlebih dahulu');
        }

        // Ambil 5 pembayaran terbaru (semua status)
        $pembayaran_terbaru = PembayaranSpp::with(['santri'])
            ->where('santri_id', $santri->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(5)
            ->get();

        return view('wali.dashboard', compact('santri', 'santri_list', 'pembayaran_terbaru'));
    }

    public function changeSantri(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id'
        ]);

        $santri = Santri::where('id', $request->santri_id)
            ->where('wali_id', Auth::id())
            ->firstOrFail();

        session(['santri_aktif' => $santri->id]);

        return redirect()->back()
            ->with('success', 'Berhasil mengubah santri aktif');
    }
}
