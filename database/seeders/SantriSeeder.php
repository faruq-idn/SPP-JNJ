<?php

namespace Database\Seeders;

use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\User;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    public function run()
    {
        // Buat kategori santri
        $kategori = KategoriSantri::create([
            'nama' => 'Reguler',
            'keterangan' => 'Santri dengan biaya normal'
        ]);

        // Ambil ID wali santri
        $waliIds = User::where('role', 'wali')->pluck('id');

        // Data dummy santri
        $santriData = [
            [
                'nisn' => '1234567890',
                'nama' => 'Ahmad Faiz',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-05-15',
                'alamat' => 'Jl. Pesantren No. 123, Malang',
                'wali_id' => $waliIds[0], // Ahmad Fauzi
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '7A',
                'status' => 'aktif',
                'kategori_id' => $kategori->id
            ],
            [
                'nisn' => '1234567891',
                'nama' => 'Siti Aisyah',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2009-08-20',
                'alamat' => 'Jl. Soekarno Hatta No. 45, Malang',
                'wali_id' => $waliIds[1], // Siti Aminah
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '8B',
                'status' => 'aktif',
                'kategori_id' => $kategori->id
            ],
            [
                'nisn' => '1234567892',
                'nama' => 'Muhammad Haikal',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2011-03-10',
                'alamat' => 'Jl. Bandung No. 78, Malang',
                'wali_id' => $waliIds[2], // Muhammad Hasan
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '7B',
                'status' => 'aktif',
                'kategori_id' => $kategori->id
            ],
            [
                'nisn' => '1234567893',
                'nama' => 'Fatimah Azzahra',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-12-25',
                'alamat' => 'Jl. Jakarta No. 56, Malang',
                'wali_id' => $waliIds[3], // Nur Fatimah
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '8A',
                'status' => 'aktif',
                'kategori_id' => $kategori->id
            ],
            [
                'nisn' => '1234567894',
                'nama' => 'Abdullah Umar',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2009-11-30',
                'alamat' => 'Jl. Surabaya No. 90, Malang',
                'wali_id' => $waliIds[4], // Abdullah
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '8C',
                'status' => 'aktif',
                'kategori_id' => $kategori->id
            ],
        ];

        foreach ($santriData as $data) {
            Santri::create($data);
        }
    }
}
