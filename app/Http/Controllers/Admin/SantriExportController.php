<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\KategoriSantri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Log;

class SantriExportController extends Controller
{
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
                    'detail' => implode('<br>', $errors)
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

    private function validateImportRow($row)
    {
        // Format validation for dates
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

        // Validation rules
        $rules = [
            'nisn' => 'required|unique:santri,nisn',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'alamat' => 'required',
            'nama_wali' => 'required|string',
            'jenjang' => 'required|in:SMP,SMA',
            'kelas' => [
                'required',
                function ($attribute, $value, $fail) use ($row) {
                    $kelas = [
                        'SMP' => ['7A', '7B', '8A', '8B', '9A', '9B'],
                        'SMA' => ['10A', '10B', '11A', '11B', '12A', '12B']
                    ];

                    if (!isset($row['jenjang']) || !isset($kelas[$row['jenjang']])) {
                        $fail('Jenjang tidak valid');
                        return;
                    }

                    if (!in_array($value, $kelas[$row['jenjang']])) {
                        $fail('Kelas tidak sesuai dengan jenjang');
                    }
                }
            ],
            'status' => 'nullable|in:aktif,non-aktif'
        ];

        return validator($row, $rules)->validate();
    }
}
