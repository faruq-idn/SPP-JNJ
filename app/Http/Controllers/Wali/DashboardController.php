<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $santri = Santri::where('wali_id', Auth::id())->first();
            $pembayaran = PembayaranSpp::where('santri_id', $santri->id ?? 0)
                ->latest()
                ->take(5)
                ->get();

            return response()
                ->view('wali.dashboard', compact('santri', 'pembayaran'))
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        } catch (\Exception $e) {
            Log::error('Error in WaliDashboard: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }
}
