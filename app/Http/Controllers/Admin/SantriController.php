<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use App\Models\KategoriSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $santri = Santri::with(['kategori', 'wali'])
            ->latest()
            ->get();

        return view('admin.santri.index', compact('santri'));
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
        ], [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah digunakan',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'wali_id.required' => 'Wali santri wajib dipilih',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
            'jenjang.required' => 'Jenjang wajib dipilih',
            'kelas.required' => 'Kelas wajib dipilih',
            'kategori_id.required' => 'Kategori wajib dipilih'
        ]);

        $santri = Santri::create($validated);

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function edit(Santri $santri)
    {
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
        $santri->load(['wali', 'kategori', 'pembayaran' => function($query) {
            $query->latest()->take(5);
        }]);

        // Hitung total tunggakan
        $totalTunggakan = 0; // Logika perhitungan tunggakan akan ditambahkan nanti

        return view('admin.santri.show', compact('santri', 'totalTunggakan'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q');

        $santri = Santri::select('id', 'nisn', 'nama', 'jenjang', 'kelas', 'kategori_id')
            ->with('kategori:id,nama')
            ->where(function($query) use ($keyword) {
                // Pencarian nama yang mirip
                $query->where('nama', 'LIKE', "%{$keyword}%")
                      ->orWhere('nama', 'LIKE', "{$keyword}%") // Awalan sama
                      ->orWhere('nama', 'LIKE', "% {$keyword}%") // Kata kedua dst
                      // Pencarian NISN yang mirip
                      ->orWhere('nisn', 'LIKE', "%{$keyword}%")
                      ->orWhere('nisn', 'LIKE', "{$keyword}%");
            })
            ->orderByRaw("
                CASE
                    WHEN nama LIKE '{$keyword}%' THEN 1
                    WHEN nama LIKE '% {$keyword}%' THEN 2
                    WHEN nisn LIKE '{$keyword}%' THEN 3
                    ELSE 4
                END
            ") // Urutkan berdasarkan kemiripan
            ->limit(10)
            ->get()
            ->map(function($santri) {
                return [
                    'id' => $santri->id,
                    'text' => $santri->nisn . ' - ' . $santri->nama,
                    'nama' => $santri->nama,
                    'nisn' => $santri->nisn,
                    'kelas' => $santri->jenjang . ' ' . $santri->kelas,
                    'kategori' => $santri->kategori->nama ?? '-'
                ];
            });

        return response()->json($santri);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            // Ambil ID kategori reguler
            $kategoriReguler = KategoriSantri::where('nama', 'Reguler')->first();
            if (!$kategoriReguler) {
                throw new \Exception('Kategori Reguler tidak ditemukan');
            }

            $collection = (new FastExcel)->import($request->file('file'));

            $imported = 0;
            $errors = [];

            foreach ($collection as $line => $row) {
                try {
                    // Validasi data
                    $validated = $this->validateImportRow($row);

                    // Import data
                    Santri::create([
                        'nisn' => $validated['nisn'],
                        'nama' => $validated['nama'],
                        'jenis_kelamin' => $validated['jenis_kelamin'],
                        'tanggal_lahir' => $validated['tanggal_lahir'],
                        'alamat' => $validated['alamat'],
                        'nama_wali' => $validated['nama_wali'],
                        'wali_id' => DB::raw('NULL'),
                        'tanggal_masuk' => $validated['tanggal_masuk'],
                        'jenjang' => $validated['jenjang'],
                        'kelas' => $validated['kelas'],
                        'kategori_id' => $kategoriReguler->id,
                        'status' => $validated['status'] ?? 'aktif'
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($line + 2) . ": " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                return response()->json([
                    'status' => 'warning',
                    'message' => "Berhasil import {$imported} data. Terdapat " . count($errors) . " data yang gagal.",
                    'errors' => $errors,
                    'detail' => implode('<br>', $errors)  // Tambahkan detail error
                ], 422);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil import {$imported} data santri"
            ]);

        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateImportRow($row)
    {
        // Konversi format tanggal jika ada
        if (!empty($row['tanggal_lahir'])) {
            try {
                $row['tanggal_lahir'] = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
            } catch (\Exception $e) {
                throw new \Exception('Format tanggal lahir tidak valid');
            }
        }

        if (!empty($row['tanggal_masuk'])) {
            try {
                $row['tanggal_masuk'] = Carbon::parse($row['tanggal_masuk'])->format('Y-m-d');
            } catch (\Exception $e) {
                throw new \Exception('Format tanggal masuk tidak valid');
            }
        }

        $rules = [
            'nisn' => 'required|unique:santri,nisn',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'alamat' => 'required',
            'nama_wali' => 'required|string',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => 'required',
            'status' => 'nullable|in:aktif,non-aktif'
        ];

        return validator($row, $rules)->validate();
    }

    public function downloadTemplate()
    {
        $data = collect([
            [
                'nisn' => 'NISN (8-10 digit)',
                'nama' => 'Nama Lengkap',
                'jenis_kelamin' => 'L/P',
                'tanggal_lahir' => 'Format: 2024-01-31',
                'alamat' => 'Alamat Lengkap',
                'nama_wali' => 'Nama Wali Santri',
                'tanggal_masuk' => 'YYYY-MM-DD',
                'jenjang' => 'SMP/SMA',
                'kelas' => 'Contoh: 7A, 8B, 9A, 10A, 11B, 12A',
                'status' => 'aktif/non-aktif'
            ],
            // Baris kosong untuk diisi
            [
                'nisn' => null,
                'nama' => null,
                'jenis_kelamin' => null,
                'tanggal_lahir' => null,
                'alamat' => null,
                'nama_wali' => null,
                'tanggal_masuk' => null,
                'jenjang' => null,
                'kelas' => null,
                'status' => null
            ]
        ]);

        return (new FastExcel($data))->download('template_import_santri.xlsx');
    }

    public function kelas($jenjang, $kelas)
    {
        $jenjang = strtoupper($jenjang);
        $santri = Santri::with(['kategori', 'wali'])
            ->where('jenjang', $jenjang)
            ->where('kelas', $kelas)
            ->latest()
            ->get();

        return view('admin.santri.index', [
            'santri' => $santri,
            'title' => "Data Santri Kelas {$kelas} {$jenjang}"
        ]);
    }
}
