<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class PembayaranController extends Controller
{
    public function index()
    {
        $belumLunas = PembayaranSpp::with(['santri'])
            ->where('status', '!=', 'success')
            ->latest('created_at')
            ->paginate(10, ['*'], 'belum_lunas');

        $lunas = PembayaranSpp::with(['santri'])
            ->where('status', 'success')
            ->latest('tanggal_bayar')
            ->paginate(10, ['*'], 'lunas');

        return view('admin.pembayaran.index', [
            'title' => 'Data Pembayaran SPP',
            'belumLunas' => $belumLunas,
            'lunas' => $lunas
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
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            // Cek apakah pembayaran sudah ada
            $exists = PembayaranSpp::where([
                'santri_id' => $validated['santri_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'status' => 'success'
            ])->exists();

            if ($exists) {
                return back()->with('error', 'Pembayaran untuk periode ini sudah lunas');
            }

            // Simpan pembayaran
            $validated['status'] = 'success';
            PembayaranSpp::create($validated);

            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran SPP berhasil disimpan');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['santri', 'metode_pembayaran']);

        return response()->json([
            'id' => $pembayaran->id,
            'santri' => [
                'nama' => $pembayaran->santri->nama,
                'nisn' => $pembayaran->santri->nisn,
                'kelas' => $pembayaran->santri->jenjang . ' ' . $pembayaran->santri->kelas,
                'kategori' => $pembayaran->santri->kategori->nama ?? '-'
            ],
            'pembayaran' => [
                'tanggal' => $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-',
                'bulan' => \Carbon\Carbon::createFromFormat('m', $pembayaran->bulan)->translatedFormat('F'),
                'tahun' => $pembayaran->tahun,
                'nominal' => number_format($pembayaran->nominal, 0, ',', '.'),
                'metode' => $pembayaran->metode_pembayaran->nama ?? 'Manual',
                'status' => ucfirst($pembayaran->status),
                'keterangan' => $pembayaran->keterangan ?? '-'
            ]
        ]);
    }

    public function generateTagihan(Request $request)
    {
        try {
            $period = $request->input('period');

            // Jalankan artisan command
            $exitCode = Artisan::call('tagihan:generate', [
                '--bulan' => $period
            ]);

            if ($exitCode === 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil generate tagihan'
                ]);
            }

            throw new \Exception('Gagal generate tagihan');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function print(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['santri.kategori', 'metode_pembayaran']);

        return view('admin.pembayaran.print', [
            'pembayaran' => $pembayaran
        ]);
    }

    public function hapusTagihan(Request $request)
    {
        try {
            $request->validate([
                'period' => 'required|date_format:Y-m'
            ]);

            $period = \Carbon\Carbon::createFromFormat('Y-m', $request->period);
            $bulan = str_pad($period->format('n'), 2, '0', STR_PAD_LEFT);
            $tahun = $period->format('Y');

            // Hapus tagihan yang belum dibayar
            $deleted = PembayaranSpp::where([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'pending'
            ])->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil menghapus {$deleted} tagihan",
                'data' => [
                    'count' => $deleted
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus tagihan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus(Request $request)
    {
        $exists = PembayaranSpp::where([
            'santri_id' => $request->santri_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'status' => 'success'
        ])->exists();

        return response()->json([
            'status' => $exists ? 'lunas' : 'belum'
        ]);
    }
}
