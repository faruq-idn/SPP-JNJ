<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\Exports\PdfExportController;

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
            'tahun' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'bulan' => 'required|string|min:3|max:20',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Convert bulan name to number if string
            try {
                $bulan_angka = is_numeric($request->bulan) ?
                    intval($request->bulan) :
                    Carbon::createFromLocaleFormat('F', 'id', $request->bulan)->month;

                if ($bulan_angka < 1 || $bulan_angka > 12) {
                    throw new \Exception('Bulan tidak valid');
                }
            } catch (\Exception $e) {
                throw new \Exception('Format bulan tidak valid');
            }

            // Check if payment already exists for this period
            $exists = PembayaranSpp::where('santri_id', $request->santri_id)
                ->where('tahun', $request->tahun)
                ->where('bulan', $bulan_angka)
                ->exists();

            if ($exists) {
                throw new \Exception('Tagihan untuk periode tersebut sudah ada');
            }

            // Buat dan verifikasi pembayaran
            $pembayaran = PembayaranSpp::create([
                'santri_id' => $request->santri_id,
                'tahun' => $request->tahun,
                'bulan' => $bulan_angka,
                'nominal' => $request->nominal,
                'status' => 'success',
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'keterangan' => $request->keterangan,
                'tanggal_bayar' => now()
            ]);

            DB::commit();

            // Load data yang diperlukan
            $pembayaran->load('metode_pembayaran');

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil disimpan',
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => 'success',
                    'tanggal_bayar' => $pembayaran->tanggal_bayar->format('d/m/Y'),
                    'metode' => $pembayaran->metode_pembayaran->nama
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Validasi status pembayaran
        $pembayaran = PembayaranSpp::findOrFail($id);
        if ($pembayaran->status === 'success') {
            throw new \Exception('Pembayaran sudah diverifikasi sebelumnya');
        }

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

            // Load relasi yang diperlukan
            $pembayaran->load('metode_pembayaran');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi',
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => 'success',
                    'tanggal_bayar' => $pembayaran->tanggal_bayar->format('d/m/Y'),
                    'metode' => $pembayaran->metode_pembayaran->nama
                ]
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
        Log::info('Memulai proses generate tagihan', ['tahun' => $request->tahun, 'bulan' => $request->bulan]);
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
            
            Log::info('Jumlah santri aktif ditemukan', ['count' => $santriList->count()]);

            $generated = 0;
            $errors = 0;

            foreach ($santriList as $santri) {
                // Skip if no tarif set
                if (!$santri->kategori->tarifTerbaru) {
                    $errors++;
                    Log::warning('Santri dilewati karena tidak memiliki tarif terbaru', ['santri_id' => $santri->id]);
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
                    Log::info('Tagihan berhasil dibuat', ['santri_id' => $santri->id, 'tahun' => $request->tahun, 'bulan' => $request->bulan]);
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
            Log::info('Proses generate tagihan selesai', ['generated' => $generated, 'errors' => $errors]);
            
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

    public function checkStatus($id)
    {
        try {
            $pembayaran = PembayaranSpp::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
    }

    public function destroy(PembayaranSpp $pembayaran)
    {
        try {
            DB::beginTransaction();
            
            $pembayaran->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cetakPembayaran(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['santri', 'metode_pembayaran']);
        $exporter = app(PdfExportController::class);
        return $exporter->exportPembayaranDetail($pembayaran);
    }
}
