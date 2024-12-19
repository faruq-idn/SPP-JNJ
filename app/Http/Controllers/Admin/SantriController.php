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
use App\Models\PembayaranSpp;

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
        // Load relasi yang dibutuhkan
        $santri->load(['wali', 'kategori']);

        // Hitung total tunggakan
        $totalTunggakan = PembayaranSpp::where('santri_id', $santri->id)
            ->where('status', 'pending')
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

        return view('admin.santri.show', compact('santri', 'totalTunggakan', 'pembayaranPerTahun'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q');

        return Santri::with(['kategori.tarifTerbaru', 'pembayaran' => function($query) {
                $query->where('status', 'pending')
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

        // Tambahkan informasi kelas untuk tampilan
        $title = "Data Santri Kelas {$kelas} {$jenjang}";
        $currentKelas = [
            'jenjang' => $jenjang,
            'kelas' => $kelas
        ];

        return view('admin.santri.index', compact('santri', 'title', 'currentKelas'));
    }

    public function pembayaran(Santri $santri)
    {
        $pembayaran = $santri->pembayaran()
            ->select('id', 'bulan', 'tahun', 'nominal', 'status', 'tanggal_bayar')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get()
            ->map(function($p) {
                $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return [
                    'bulan_nama' => $bulan[$p->bulan - 1],
                    'tahun' => $p->tahun,
                    'nominal' => $p->nominal,
                    'status' => $p->status,
                    'tanggal_bayar' => $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : null
                ];
            });

        return response()->json([
            'santri' => [
                'nama' => $santri->nama,
                'nisn' => $santri->nisn,
                'kelas' => $santri->jenjang . ' ' . $santri->kelas,
                'kategori' => $santri->kategori->nama
            ],
            'pembayaran' => $pembayaran
        ]);
    }

    public function kenaikanKelas()
    {
        try {
            DB::beginTransaction();

            // Nonaktifkan santri kelas akhir (9 SMP dan 12 SMA)
            Santri::where('status', 'aktif')
                ->where(function($query) {
                    $query->where(function($q) {
                        $q->where('jenjang', 'SMP')
                          ->where('kelas', 'LIKE', '9%');
                    })->orWhere(function($q) {
                        $q->where('jenjang', 'SMA')
                          ->where('kelas', 'LIKE', '12%');
                    });
                })
                ->update(['status' => 'non-aktif']);

            // Kenaikan kelas untuk SMP
            foreach(['7A' => '8A', '7B' => '8B', '8A' => '9A', '8B' => '9B'] as $dari => $ke) {
                Santri::where('status', 'aktif')
                    ->where('jenjang', 'SMP')
                    ->where('kelas', $dari)
                    ->update(['kelas' => $ke]);
            }

            // Kenaikan kelas untuk SMA
            foreach(['10A' => '11A', '10B' => '11B', '11A' => '12A', '11B' => '12B'] as $dari => $ke) {
                Santri::where('status', 'aktif')
                    ->where('jenjang', 'SMA')
                    ->where('kelas', $dari)
                    ->update(['kelas' => $ke]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memproses kenaikan kelas'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses kenaikan kelas: ' . $e->getMessage()
            ], 500);
        }
    }
}
