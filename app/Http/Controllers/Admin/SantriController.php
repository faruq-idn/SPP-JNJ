<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSantri;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::with(['kategori', 'wali'])->latest()->paginate(10);
        return view('admin.santri.index', compact('santri'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getPathname());
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $imported = 0;
        $errors = [];

        foreach ($records as $offset => $record) {
            try {
                // Validasi data
                if (empty($record['nisn']) || empty($record['nama'])) {
                    $errors[] = "Baris " . ($offset + 2) . ": NISN dan Nama harus diisi";
                    continue;
                }

                // Cek duplikat NISN
                if (Santri::where('nisn', $record['nisn'])->exists()) {
                    $errors[] = "Baris " . ($offset + 2) . ": NISN {$record['nisn']} sudah terdaftar";
                    continue;
                }

                // Cek keberadaan kategori
                $kategori = KategoriSantri::find($record['kategori_id']);
                if (!$kategori) {
                    $errors[] = "Baris " . ($offset + 2) . ": Kategori tidak ditemukan";
                    continue;
                }

                // Cek keberadaan wali
                $wali = User::where('role', 'wali')->find($record['wali_id']);
                if (!$wali) {
                    $errors[] = "Baris " . ($offset + 2) . ": Wali Santri tidak ditemukan";
                    continue;
                }

                // Buat santri baru
                Santri::create([
                    'nisn' => $record['nisn'],
                    'nama' => $record['nama'],
                    'jenis_kelamin' => $record['jenis_kelamin'],
                    'tanggal_lahir' => $record['tanggal_lahir'],
                    'alamat' => $record['alamat'],
                    'wali_id' => $record['wali_id'],
                    'tanggal_masuk' => $record['tanggal_masuk'],
                    'jenjang' => strtoupper($record['jenjang']),
                    'kelas' => strtoupper($record['kelas']),
                    'kategori_id' => $record['kategori_id'],
                    'status' => 'aktif',
                    'status_spp' => 'Belum Lunas'
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($offset + 2) . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil mengimport {$imported} data santri.";
        if (count($errors) > 0) {
            $message .= "\nTerdapat " . count($errors) . " error:\n" . implode("\n", $errors);
            return redirect()->route('admin.santri.index')->with('warning', $message);
        }

        return redirect()->route('admin.santri.index')->with('success', $message);
    }

    public function kelas($jenjang, $kelas)
    {
        $santri = Santri::with(['kategori', 'wali'])
            ->where('jenjang', strtoupper($jenjang))
            ->where('kelas', strtoupper($kelas))
            ->where('status', 'aktif')
            ->latest()
            ->paginate(10);

        $currentKelas = [
            'jenjang' => strtoupper($jenjang),
            'kelas' => strtoupper($kelas)
        ];

        return view('admin.santri.index', compact('santri', 'currentKelas'));
    }

    public function create()
    {
        $kategori_santri = KategoriSantri::all();
        $wali_santri = User::where('role', 'wali')->get();
        return view('admin.santri.create', compact('kategori_santri', 'wali_santri'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:santri',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required|string|max:3',
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,lulus,keluar'
        ]);

        $validated['status_spp'] = 'Belum Lunas';

        Santri::create($validated);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function show(Santri $santri)
    {
        $santri->load(['kategori', 'wali', 'pembayaran']);
        return view('admin.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        $kategori_santri = KategoriSantri::all();
        return view('admin.santri.edit', compact('santri', 'kategori_santri'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:santri,nisn,' . $santri->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'wali_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required|string|max:3',
            'kategori_id' => 'required|exists:kategori_santri,id',
            'status' => 'required|in:aktif,lulus,keluar'
        ]);

        $santri->update($validated);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Santri $santri)
    {
        if ($santri->pembayaran()->exists()) {
            return back()->with('error', 'Santri tidak dapat dihapus karena memiliki riwayat pembayaran');
        }

        $santri->delete();

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil dihapus');
    }

    public function kenaikanKelas(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'santri_data' => 'required|array',
                'santri_data.*.id' => 'required|exists:santri,id',
                'santri_data.*.kelasTujuan' => 'nullable|string|max:3',
                'santri_data.*.jenjang' => 'required|in:SMP,SMA',
                'santri_data.*.status' => 'required|in:aktif,lulus'
            ]);

            $santriData = $request->input('santri_data');

            if (empty($santriData)) {
                throw new \Exception('Tidak ada data santri yang dipilih');
            }

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi data santri gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            $santri = Santri::whereIn('id', collect($santriData)->pluck('id'))->get();

            $santriCollection = collect($santriData);
            
            foreach ($santri as $s) {
                $dataTujuan = $santriCollection->first(function ($item) use ($s) {
                    return $item['id'] == $s->id;
                });
                
                if (!$dataTujuan) {
                    Log::warning("Data tujuan tidak ditemukan untuk santri ID: {$s->id}");
                    continue;
                }

                // Debug log
                Log::info("Processing santri:", [
                    'id' => $s->id,
                    'data_tujuan' => $dataTujuan
                ]);

                // Jika status lulus
                if ($dataTujuan['status'] === 'lulus') {
                    $s->riwayatKenaikanKelas()->create([
                        'santri_id' => $s->id,
                        'jenjang_awal' => $s->jenjang,
                        'kelas_awal' => $s->kelas,
                        'status_awal' => $s->status,
                        'jenjang_akhir' => $s->jenjang,
                        'kelas_akhir' => $s->kelas,
                        'status_akhir' => 'lulus',
                        'created_by' => Auth::id()
                    ]);

                    $s->update(['status' => 'lulus']);
                }
                // Jika naik kelas
                else {
                    $s->riwayatKenaikanKelas()->create([
                        'santri_id' => $s->id,
                        'jenjang_awal' => $s->jenjang,
                        'kelas_awal' => $s->kelas,
                        'status_awal' => $s->status,
                        'jenjang_akhir' => $dataTujuan['jenjang'],
                        'kelas_akhir' => $dataTujuan['kelasTujuan'],
                        'status_akhir' => 'aktif',
                        'created_by' => Auth::id()
                    ]);

                    $s->update([
                        'jenjang' => $dataTujuan['jenjang'],
                        'kelas' => $dataTujuan['kelasTujuan'],
                        'status' => 'aktif'
                    ]);
                }
            }

            DB::commit();
            
            return response()->json([
                'message' => 'Kenaikan kelas berhasil diproses'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batalKenaikanKelas(Request $request)
    {
        try {
            $request->validate([
                'santri_ids' => 'required|array',
                'santri_ids.*' => 'exists:santri,id'
            ]);

            DB::beginTransaction();

            $santriIds = $request->input('santri_ids');
            
            // Ambil data santri yang akan dibatalkan kenaikan kelasnya
            $santri = Santri::whereIn('id', $santriIds)
                ->with(['riwayatKenaikanKelas' => function($query) {
                    $query->latest();
                }])
                ->get();

            if ($santri->isEmpty()) {
                throw new \Exception('Tidak ada santri yang dipilih');
            }

            foreach ($santri as $s) {
                if ($s->riwayatKenaikanKelas->isNotEmpty()) {
                    $riwayatTerakhir = $s->riwayatKenaikanKelas->first();
                    
                    // Kembalikan ke kelas sebelumnya
                    $s->update([
                        'kelas' => $riwayatTerakhir->kelas_awal,
                        'jenjang' => $riwayatTerakhir->jenjang_awal,
                        'status' => $riwayatTerakhir->status_awal
                    ]);
                    
                    // Hapus riwayat kenaikan kelas terakhir
                    $riwayatTerakhir->delete();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Pembatalan kenaikan kelas berhasil diproses'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
