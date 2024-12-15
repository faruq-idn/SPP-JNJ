<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = PembayaranSpp::with('santri')
            ->latest('tanggal_bayar')
            ->paginate(10);

        // Data untuk dropdown di form
        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $tahun = range(date('Y') - 2, date('Y') + 1);

        return view('admin.pembayaran.index', [
            'title' => 'Pembayaran SPP',
            'pembayaran' => $pembayaran,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    public function create()
    {
        // Data untuk dropdown bulan
        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Data untuk dropdown tahun (2 tahun ke belakang sampai 1 tahun ke depan)
        $tahun = range(date('Y') - 2, date('Y') + 1);

        return view('admin.pembayaran.create', compact('bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'tanggal_bayar' => 'required|date',
            'bulan' => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'tahun' => 'required|digits:4',
            'nominal' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $validated['petugas_id'] = Auth::id();
        $validated['status'] = 'success';

        PembayaranSpp::create($validated);

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran SPP berhasil disimpan');
    }
}
