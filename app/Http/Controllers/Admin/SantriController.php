<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSantri;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use League\Csv\Reader;

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
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santri,id',
            'kelas_tujuan' => 'required|string|max:3'
        ]);

        $santriIds = $request->input('santri_ids');
        $kelasTujuan = $request->input('kelas_tujuan');

        // Ambil data santri yang akan dinaikan kelasnya
        $santri = Santri::whereIn('id', $santriIds)->get();

        foreach ($santri as $s) {
            // Simpan riwayat kenaikan kelas
            $s->riwayatKenaikanKelas()->create([
                'jenjang_awal' => $s->jenjang,
                'kelas_awal' => $s->kelas,
                'status_awal' => $s->status,
                'jenjang_akhir' => $s->jenjang,
                'kelas_akhir' => $kelasTujuan,
                'status_akhir' => 'aktif',
                'created_by' => request()->user()->id
            ]);

            // Update kelas santri
            $s->update(['kelas' => $kelasTujuan]);
        }

        return response()->json([
            'message' => 'Kenaikan kelas berhasil diproses'
        ]);
    }

    public function batalKenaikanKelas(Request $request)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santri,id'
        ]);

        $santriIds = $request->input('santri_ids');

        // Ambil data santri yang akan dibatalkan kenaikan kelasnya
        $santri = Santri::whereIn('id', $santriIds)
            ->with(['riwayatKenaikanKelas' => function($query) {
                $query->latest();
            }])
            ->get();

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

        return response()->json([
            'message' => 'Pembatalan kenaikan kelas berhasil diproses'
        ]);
    }
}
