<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index()
    {
        $totalBelumLunas = PembayaranSpp::where('status', '!=', 'success')->count();
        $pembayaranPending = PembayaranSpp::with(['santri', 'metode_pembayaran'])
            ->where('status', '!=', 'success')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending')
            ->appends(request()->except('pending'));

        $totalLunas = PembayaranSpp::where('status', 'success')->count();
        $pembayaranLunas = PembayaranSpp::with(['santri', 'metode_pembayaran'])
            ->where('status', 'success')
            ->orderBy('tanggal_bayar', 'desc')
            ->paginate(10, ['*'], 'lunas')
            ->appends(request()->except('lunas'));

        return view('admin.pembayaran.index', compact(
            'totalBelumLunas',
            'pembayaranPending',
            'totalLunas',
            'pembayaranLunas'
        ));
    }

    public function create()
    {
        $santri = Santri::where('status', 'aktif')->get();
        return view('admin.pembayaran.create', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'tahun' => 'required|digits:4',
            'bulan' => 'required|numeric|min:1|max:12',
        ]);

        try {
            DB::beginTransaction();

            // Check if payment already exists
            $exists = PembayaranSpp::where([
                'santri_id' => $request->santri_id,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan
            ])->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->with('error', 'Tagihan untuk periode tersebut sudah ada');
            }

            // Get santri tarif
            $santri = Santri::with('kategori.tarifTerbaru')->findOrFail($request->santri_id);
            if (!$santri->kategori->tarifTerbaru) {
                return redirect()
                    ->back()
                    ->with('error', 'Tarif SPP untuk kategori santri belum diatur');
            }

            PembayaranSpp::create([
                'santri_id' => $request->santri_id,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'nominal' => $santri->kategori->tarifTerbaru->nominal,
                'status' => 'unpaid'
            ]);

            DB::commit();
            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', 'Tagihan berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['santri', 'metode_pembayaran']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = PembayaranSpp::findOrFail($id);
            $pembayaran->update([
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateTagihan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4',
            'bulan' => 'required|numeric|min:1|max:12',
        ]);

        try {
            DB::beginTransaction();

            // Get all active santri with their latest tarif
            $santriList = Santri::with('kategori.tarifTerbaru')
                ->where('status', 'aktif')
                ->get();

            $generated = 0;
            $errors = 0;

            foreach ($santriList as $santri) {
                // Skip if no tarif set
                if (!$santri->kategori->tarifTerbaru) {
                    $errors++;
                    continue;
                }

                // Check if payment already exists
                $exists = PembayaranSpp::where([
                    'santri_id' => $santri->id,
                    'tahun' => $request->tahun,
                    'bulan' => $request->bulan
                ])->exists();

                if (!$exists) {
                    PembayaranSpp::create([
                        'santri_id' => $santri->id,
                        'tahun' => $request->tahun,
                        'bulan' => $request->bulan,
                        'nominal' => $santri->kategori->tarifTerbaru->nominal,
                        'status' => 'unpaid'
                    ]);
                    $generated++;
                }
            }

            DB::commit();

            $message = "Berhasil generate {$generated} tagihan.";
            if ($errors > 0) {
                $message .= " {$errors} santri dilewati karena tidak memiliki tarif.";
            }

            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapusTagihan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4',
            'bulan' => 'required|numeric|min:1|max:12',
        ]);

        try {
            DB::beginTransaction();

            // Only delete unpaid payments
            $deleted = PembayaranSpp::where([
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'status' => 'unpaid'
            ])->delete();

            DB::commit();

            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', "Berhasil menghapus {$deleted} tagihan yang belum dibayar.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkStatus(Request $request)
    {
        $pembayaran = PembayaranSpp::find($request->id);
        
        if (!$pembayaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'status' => $pembayaran->status,
                'message' => 'Status pembayaran: ' . ucfirst($pembayaran->status)
            ]
        ]);
    }
}
