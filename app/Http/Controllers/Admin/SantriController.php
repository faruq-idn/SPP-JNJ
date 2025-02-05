<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use App\Models\KategoriSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PembayaranSpp;
use App\Models\KenaikanKelasHistory;
use Illuminate\Support\Facades\Auth;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $santri = Santri::with(['kategori', 'wali'])
            ->latest()
            ->get();

        return view('admin.santri.index', compact('santri'));
    }

    public function kelas($jenjang, $kelas)
    {
        $jenjang = strtoupper($jenjang);
        $santri = Santri::with(['kategori', 'wali'])
            ->where('jenjang', $jenjang)
            ->where('kelas', $kelas)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $currentKelas = [
            'jenjang' => $jenjang,
            'kelas' => $kelas
        ];

        $title = "Data Santri Kelas {$kelas} {$jenjang}";

        return view('admin.santri.index', compact('santri', 'currentKelas', 'title'));
    }

    public function create()
    {
        $kategori = KategoriSantri::all();
        $wali = User::where('role', 'wali')->get();
        $jenjang = ['SMP', 'SMA'];
        $kelas = [
            'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
        ];

        return view('admin.santri.create', compact('kategori', 'wali', 'jenjang', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:santri',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $kelas = [
                        'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
                        'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
                    ];

                    if (!isset($request->jenjang) || !isset($kelas[$request->jenjang])) {
                        $fail('Pilih jenjang terlebih dahulu.');
                        return;
                    }

                    if (!in_array($value, $kelas[$request->jenjang])) {
                        $fail('Kelas tidak sesuai dengan jenjang yang dipilih.');
                    }
                },
            ],
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,non-aktif'
        ]);

        $santri = Santri::create($validated);

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function edit(Santri $santri)
    {
        // Simpan URL sebelumnya ke session
        session(['santri_previous_url' => url()->previous()]);

        $kategori = KategoriSantri::all();
        $wali = User::where('role', 'wali')->get();
        $jenjang = ['SMP', 'SMA'];
        $kelas = [
            'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
        ];

        return view('admin.santri.edit', compact('santri', 'kategori', 'wali', 'jenjang', 'kelas'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:santri,nisn,'.$santri->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required',
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,non-aktif'
        ]);

        $santri->update($validated);

        // Redirect ke URL sebelumnya jika ada
        if ($previousUrl = session('santri_previous_url')) {
            session()->forget('santri_previous_url');
            return redirect($previousUrl)
                ->with('success', 'Data santri berhasil diperbarui');
        }

        // Default: kembali ke index
        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Santri $santri)
    {
        try {
            DB::beginTransaction();

            // Hapus semua pembayaran terkait
            $santri->pembayaran()->delete();

            // Hapus data santri
            $santri->delete();

            DB::commit();

            return redirect()
                ->route('admin.santri.index')
                ->with('success', 'Data santri dan semua data terkait berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data santri. Silakan coba lagi.');
        }
    }

    public function show(Santri $santri)
    {
        $santri->load([
            'wali',
            'kategori',
            'pembayaran' => function ($query) {
                $query->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc');
            },
        ]);

        // Hitung total tunggakan
        $totalTunggakan = PembayaranSpp::where('santri_id', $santri->id)
            ->whereIn('status', ['unpaid', 'pending'])
            ->sum('nominal');

        // Ambil tahun-tahun yang memiliki pembayaran
        $tahunPembayaran = PembayaranSpp::where('santri_id', $santri->id)
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Jika belum ada data tahun, gunakan tahun sekarang
        if (empty($tahunPembayaran)) {
            $tahunPembayaran = [date('Y')];
        }

        // Siapkan data pembayaran per tahun
        $pembayaranPerTahun = [];
        foreach ($tahunPembayaran as $tahun) {
            $pembayaran = [];
            // Data pembayaran yang sudah ada
            $existingPembayaran = PembayaranSpp::with('metode_pembayaran')
                ->where('santri_id', $santri->id)
                ->where('tahun', $tahun)
                ->get()
                ->keyBy('bulan');

            // Generate 12 bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $pembayaran[] = $existingPembayaran->get($bulanPadded) ?? (object)[
                    'bulan' => $bulanPadded,
                    'tahun' => $tahun,
                    'nominal' => $santri->kategori->tarifTerbaru->nominal ?? 0,
                    'status' => 'pending',
                    'tanggal_bayar' => null,
                    'metode_pembayaran' => null
                ];
            }

            $pembayaranPerTahun[$tahun] = $pembayaran;
        }

        return view('admin.santri.show', compact('santri', 'totalTunggakan', 'pembayaranPerTahun', 'tahunPembayaran'));
    }

    public function kenaikanKelas()
    {
        try {
            DB::beginTransaction();

                // Data kelas selanjutnya dan urutan kelas
            $nextClass = [
                'SMP' => [
                    '7A' => '8A', '7B' => '8B',
                    '8A' => '9A', '8B' => '9B',
                    '9A' => null, '9B' => null
                ],
                'SMA' => [
                    '10A' => '11A', '10B' => '11B',
                    '11A' => '12A', '11B' => '12B',
                    '12A' => null, '12B' => null
                ]
            ];

            $kelasOrder = [
                'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
                'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
            ];

            // Proses kenaikan kelas dari kelas terendah ke tertinggi
            $santriUpdated = 0;
            $santriGraduated = 0;

            foreach (['SMP', 'SMA'] as $jenjang) {
                // Proses dari kelas terendah ke tertinggi
                foreach ($kelasOrder[$jenjang] as $currentKelas) {
                    // Jika kelas akhir (9 atau 12), tandai untuk lulus
                    $isKelasAkhir = in_array($currentKelas, ['9A', '9B', '12A', '12B']);
                    $nextKelas = $isKelasAkhir ? null : $nextClass[$jenjang][$currentKelas];
                    
                    $santriKelas = Santri::where('jenjang', $jenjang)
                        ->where('kelas', $currentKelas)
                        ->where('status', 'aktif')
                        ->get();

                    foreach ($santriKelas as $santri) {
                        // Simpan data awal sebelum update
                        $history = [
                            'santri_id' => $santri->id,
                            'jenjang_awal' => $santri->jenjang,
                            'kelas_awal' => $santri->kelas,
                            'status_awal' => $santri->status,
                            'jenjang_akhir' => $santri->jenjang,
                            'kelas_akhir' => $nextKelas ?? $santri->kelas,
                            'status_akhir' => $nextKelas === null ? 'non-aktif' : 'aktif',
                            'created_by' => Auth::id()
                        ];

                        // Update status berdasarkan kelas
                        if ($isKelasAkhir) {
                            $santri->update(['status' => 'non-aktif']);
                            $santriGraduated++;
                        } elseif ($nextKelas !== null) {
                            $santri->update(['kelas' => $nextKelas]);
                            $santriUpdated++;
                        }

                        // Simpan history
                        KenaikanKelasHistory::create($history);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil memproses kenaikan kelas: {$santriUpdated} santri naik kelas, {$santriGraduated} santri lulus"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses kenaikan kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batalKenaikanKelas()
    {
        try {
            DB::beginTransaction();

            // Ambil history kenaikan kelas terakhir
            $lastHistory = KenaikanKelasHistory::orderBy('created_at', 'desc')
                ->get()
                ->groupBy('santri_id');

            if ($lastHistory->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada history kenaikan kelas yang bisa dibatalkan'
                ], 404);
            }

            $santriRestored = 0;

            foreach ($lastHistory as $santriHistories) {
                $latestHistory = $santriHistories->first();
                
                // Kembalikan data santri ke kondisi awal
                Santri::where('id', $latestHistory->santri_id)->update([
                    'jenjang' => $latestHistory->jenjang_awal,
                    'kelas' => $latestHistory->kelas_awal,
                    'status' => $latestHistory->status_awal
                ]);

                // Hapus history
                $latestHistory->delete();
                $santriRestored++;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil membatalkan kenaikan kelas untuk {$santriRestored} santri"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membatalkan kenaikan kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q');

        return Santri::with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
                $query->whereIn('status', ['unpaid', 'pending'])
                      ->orderBy('tahun')
                      ->orderBy('bulan');
            }])
            ->where(function($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%{$keyword}%")
                      ->orWhere('nisn', 'LIKE', "%{$keyword}%");
            })
            ->where('status', 'aktif')
            ->limit(10)
            ->get()
            ->map(function($santri) {
                $tunggakan = $santri->pembayaran->map(function($p) {
                    return [
                        'bulan' => $p->bulan,
                        'tahun' => $p->tahun
                    ];
                });

                return [
                    'id' => $santri->id,
                    'nama' => $santri->nama,
                    'nisn' => $santri->nisn,
                    'nominal_spp' => $santri->kategori->tarifTerbaru->nominal ?? 0,
                    'tunggakan' => $tunggakan
                ];
            });
    }
}
