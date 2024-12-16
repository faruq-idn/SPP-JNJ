<?php

namespace Database\Seeders;

use App\Models\Santri;
use App\Models\User;
use App\Models\KategoriSantri;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    public function run()
    {
        // Ambil data wali dan kategori
        $waliList = User::where('role', 'wali')->get();
        $kategoriList = KategoriSantri::all();

        // Cek ketersediaan data
        if ($waliList->isEmpty()) {
            throw new \Exception('Tidak ada data wali. Jalankan UserSeeder terlebih dahulu.');
        }

        if ($kategoriList->isEmpty()) {
            throw new \Exception('Tidak ada data kategori. Jalankan KategoriSantriSeeder terlebih dahulu.');
        }

        // Data dummy santri
        $santri = [
            [
                'nisn' => '1234567890',
                'nama' => 'Ahmad Faiz',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-05-15',
                'alamat' => 'Jl. Pesantren No. 123, Malang',
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '7A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567891',
                'nama' => 'Siti Fatimah',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2009-08-20',
                'alamat' => 'Jl. Soekarno Hatta No. 45, Malang',
                'tanggal_masuk' => '2022-07-10',
                'jenjang' => 'SMP',
                'kelas' => '8B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567892',
                'nama' => 'Muhammad Rizki',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-03-25',
                'alamat' => 'Jl. Bandung No. 78, Malang',
                'tanggal_masuk' => '2021-07-20',
                'jenjang' => 'SMP',
                'kelas' => '9A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567893',
                'nama' => 'Aisyah Putri',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2007-11-30',
                'alamat' => 'Jl. Jakarta No. 56, Malang',
                'tanggal_masuk' => '2021-07-15',
                'jenjang' => 'SMA',
                'kelas' => '10A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567894',
                'nama' => 'Abdullah Zaki',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2006-04-12',
                'alamat' => 'Jl. Surabaya No. 90, Malang',
                'tanggal_masuk' => '2020-07-10',
                'jenjang' => 'SMA',
                'kelas' => '11B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567895',
                'nama' => 'Zahra Amira',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2005-09-18',
                'alamat' => 'Jl. Veteran No. 34, Malang',
                'tanggal_masuk' => '2019-07-15',
                'jenjang' => 'SMA',
                'kelas' => '12A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567896',
                'nama' => 'Umar Hafiz',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-12-05',
                'alamat' => 'Jl. Bogor No. 67, Malang',
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '7B',
                'status' => 'non-aktif'
            ],
            [
                'nisn' => '1234567897',
                'nama' => 'Khadijah Ayu',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2009-06-22',
                'alamat' => 'Jl. Diponegoro No. 89, Malang',
                'tanggal_masuk' => '2022-07-10',
                'jenjang' => 'SMP',
                'kelas' => '8A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567898',
                'nama' => 'Hamzah Yusuf',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-02-14',
                'alamat' => 'Jl. Pahlawan No. 12, Malang',
                'tanggal_masuk' => '2021-07-20',
                'jenjang' => 'SMP',
                'kelas' => '9B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567899',
                'nama' => 'Ruqayya Safira',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2007-07-08',
                'alamat' => 'Jl. Merdeka No. 45, Malang',
                'tanggal_masuk' => '2021-07-15',
                'jenjang' => 'SMA',
                'kelas' => '10B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567900',
                'nama' => 'Ibrahim Malik',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2006-01-25',
                'alamat' => 'Jl. Gajah Mada No. 23, Malang',
                'tanggal_masuk' => '2020-07-10',
                'jenjang' => 'SMA',
                'kelas' => '11A',
                'status' => 'non-aktif'
            ],
            [
                'nisn' => '1234567901',
                'nama' => 'Asma Husna',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2005-10-30',
                'alamat' => 'Jl. Hayam Wuruk No. 56, Malang',
                'tanggal_masuk' => '2019-07-15',
                'jenjang' => 'SMA',
                'kelas' => '12B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567902',
                'nama' => 'Bilal Rasyid',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-08-17',
                'alamat' => 'Jl. Ahmad Yani No. 78, Malang',
                'tanggal_masuk' => '2023-07-15',
                'jenjang' => 'SMP',
                'kelas' => '7A',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567903',
                'nama' => 'Lubna Zahira',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2009-03-12',
                'alamat' => 'Jl. Thamrin No. 90, Malang',
                'tanggal_masuk' => '2022-07-10',
                'jenjang' => 'SMP',
                'kelas' => '8B',
                'status' => 'aktif'
            ],
            [
                'nisn' => '1234567904',
                'nama' => 'Hasan Basri',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-05-20',
                'alamat' => 'Jl. Sudirman No. 34, Malang',
                'tanggal_masuk' => '2021-07-20',
                'jenjang' => 'SMP',
                'kelas' => '9A',
                'status' => 'aktif'
            ]
        ];

        foreach ($santri as $data) {
            Santri::create([
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'wali_id' => $waliList->random()->id,
                'tanggal_masuk' => $data['tanggal_masuk'],
                'jenjang' => $data['jenjang'],
                'kelas' => $data['kelas'],
                'kategori_id' => $kategoriList->random()->id,
                'status' => $data['status']
            ]);
        }
    }
}
