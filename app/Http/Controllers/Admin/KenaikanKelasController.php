<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\KenaikanKelasHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KenaikanKelasController extends Controller
{
    public function riwayat()
    {
        $riwayat = KenaikanKelasHistory::with(['santri', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.santri.riwayat', compact('riwayat'));
    }

    public function kenaikanKelas()
    {
        DB::beginTransaction();
        try {
            // Ambil semua santri aktif
            $santriAktif = Santri::where('status', 'aktif')->get();
            $currentYear = date('Y');
            $berhasil = 0;
            $errors = [];

            foreach ($santriAktif as $santri) {
                try {
                    $kelasAwal = $santri->kelas;
                    $match = preg_match('/^(\d+)(.*)$/', $kelasAwal, $matches);

                    if (!$match) {
                        throw new \Exception("Format kelas tidak valid");
                    }

                    $tingkat = (int)$matches[1];
                    $suffix = $matches[2] ?? '';

                    // Handle kasus khusus
                    if (($santri->jenjang === 'SMP' && $tingkat === 9) ||
                        ($santri->jenjang === 'SMA' && $tingkat === 12)) {
                        // Santri lulus
                        $santri->status = 'lulus';
                        $santri->tahun_tamat = $currentYear;
                        $santri->save();

                        KenaikanKelasHistory::create([
                            'santri_id' => $santri->id,
                            'kelas_sebelum' => $kelasAwal,
                            'kelas_sesudah' => null,
                            'status' => 'lulus',
                            'created_by' => Auth::id()
                        ]);

                        $berhasil++;
                        continue;
                    }

                    // Kenaikan kelas normal
                    $kelasBaru = ($tingkat + 1) . $suffix;
                    $santri->update(['kelas' => $kelasBaru]);

                    KenaikanKelasHistory::create([
                        'santri_id' => $santri->id,
                        'kelas_sebelum' => $kelasAwal,
                        'kelas_sesudah' => $kelasBaru,
                        'status' => 'aktif',
                        'created_by' => Auth::id()
                    ]);

                    $berhasil++;

                } catch (\Exception $e) {
                    $errors[] = "Gagal memproses santri {$santri->nama}: " . $e->getMessage();
                }
            }

            if (empty($errors)) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => "Berhasil memproses kenaikan kelas untuk {$berhasil} santri"
                ]);
            }

            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => implode("\n", $errors)
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batalKenaikanKelas(Request $request)
    {
        DB::beginTransaction();
        try {
            $santriIds = $request->santri_ids;
            $histories = KenaikanKelasHistory::whereIn('santri_id', $santriIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('santri_id');

            foreach ($histories as $santriId => $riwayat) {
                if ($riwayat->isEmpty()) continue;

                $lastHistory = $riwayat->first();
                $santri = Santri::find($santriId);

                if (!$santri) continue;

                // Kembalikan ke kelas sebelumnya
                $santri->update([
                    'kelas' => $lastHistory->kelas_sebelum,
                    'status' => 'aktif',
                    'tahun_tamat' => null
                ]);

                // Hapus riwayat kenaikan kelas terakhir
                $lastHistory->delete();
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil membatalkan kenaikan kelas'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
