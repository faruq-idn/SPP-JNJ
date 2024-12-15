<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $santri = Santri::where('wali_id', Auth::id())->first();
        $pembayaran = PembayaranSpp::where('santri_id', $santri->id ?? 0)
            ->latest()
            ->take(5)
            ->get();

        return view('wali.dashboard', compact('santri', 'pembayaran'));
    }
}
