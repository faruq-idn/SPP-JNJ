<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\User;
use Carbon\Carbon;

class SantriSeeder extends Seeder
{
    protected $namaLaki = [
        'Ahmad', 'Muhammad', 'Abdullah', 'Ibrahim', 'Yusuf', 
        'Umar', 'Ali', 'Hassan', 'Zainul', 'Fadil',
        'Ridwan', 'Fahri', 'Ilham', 'Rizky', 'Amir'
    ];

    protected $namaPerempuan = [
        'Fatimah', 'Aisyah', 'Khadijah', 'Zahra', 'Aminah',
        'Nur', 'Siti', 'Dewi', 'Putri', 'Rahma',
        'Annisa', 'Fitria', 'Sarah', 'Nabila', 'Indah'
    ];

    protected $namaBelakang = [
        'Hidayat', 'Abdullah', 'Rahman', 'Syafii', 'Hakim',
        'Pratama', 'Putra', 'Saputra', 'Wijaya', 'Kusuma',
        'Arif', 'Ramadhan', 'Al-Farisi', 'Malik', 'Arifin'
    ];

    public function run()
    {
        $kategoris = KategoriSantri::all();
        $walis = User::where('role', 'wali')->get();
        $kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
        $kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];

        // Generate 50 santri with realistic data
        for ($i = 0; $i < 50; $i++) {
            $jenisKelamin = rand(0, 1) ? 'L' : 'P';
            $jenjang = rand(0, 1) ? 'SMP' : 'SMA';
            
            // Generate random tanggal masuk between 2022-2024
            $tahunMasuk = rand(2022, 2024);
            $bulanMasuk = rand(1, 12);
            // If tahun 2024, only allow up to current month
            if ($tahunMasuk == 2024) {
                $bulanMasuk = rand(1, min(date('n'), 12));
            }
            $tanggalMasuk = Carbon::create($tahunMasuk, $bulanMasuk, 15);

            // Generate nama based on gender
            if ($jenisKelamin === 'L') {
                $namaDepan = $this->namaLaki[array_rand($this->namaLaki)];
            } else {
                $namaDepan = $this->namaPerempuan[array_rand($this->namaPerempuan)];
            }
            $namaBelakang = $this->namaBelakang[array_rand($this->namaBelakang)];

            // Calculate appropriate class based on entry date
            $tahunAjaran = date('Y');
            $bulanAjaran = date('n');
            if ($bulanAjaran < 7) $tahunAjaran--; // Tahun ajaran dimulai Juli

            $tahunMasukAjaran = $tanggalMasuk->year;
            if ($tanggalMasuk->month < 7) $tahunMasukAjaran--;

            $tingkat = ($tahunAjaran - $tahunMasukAjaran) + ($jenjang === 'SMP' ? 7 : 10);
            if ($tingkat > ($jenjang === 'SMP' ? 9 : 12)) {
                $status = 'lulus';
            } else {
                $status = 'aktif';
            }

            // Select appropriate class
            if ($status === 'aktif') {
                $kelas = $jenjang === 'SMP' 
                    ? $kelasSMP[($tingkat - 7) * 2 + rand(0, 1)]
                    : $kelasSMA[($tingkat - 10) * 2 + rand(0, 1)];
            } else {
                $kelas = $jenjang === 'SMP' ? '9B' : '12B';
            }

            // Generate tanggal lahir based on jenjang
            $tahunLahir = $jenjang === 'SMP' 
                ? rand($tahunMasuk - 14, $tahunMasuk - 12)  // 12-14 tahun for SMP
                : rand($tahunMasuk - 17, $tahunMasuk - 15); // 15-17 tahun for SMA

            Santri::create([
                'nisn' => date('Y') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nama' => $namaDepan . ' ' . $namaBelakang,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => Carbon::create($tahunLahir, rand(1, 12), rand(1, 28))->format('Y-m-d'),
                'alamat' => 'Jl. ' . $this->generateStreetName() . ' No. ' . rand(1, 200),
                'wali_id' => $walis->random()->id,
                'tanggal_masuk' => $tanggalMasuk->format('Y-m-d'),
                'jenjang' => $jenjang,
                'kelas' => $kelas,
                'kategori_id' => $kategoris->random()->id,
                'status' => $status,
                'tahun_tamat' => $status === 'lulus' ? $tahunAjaran : null
            ]);
        }
    }

    protected function generateStreetName()
    {
        $prefixes = ['Jendral', 'Pangeran', 'Sultan', 'Kyai', 'Haji', 'Raya', 'Taman'];
        $names = ['Sudirman', 'Diponegoro', 'Hasanuddin', 'Wahid', 'Soekarno', 'Hatta', 'Antasari'];
        $suffixes = ['', 'I', 'II', 'Utara', 'Selatan', 'Timur', 'Barat'];

        return $prefixes[array_rand($prefixes)] . ' ' . 
               $names[array_rand($names)] . ' ' . 
               $suffixes[array_rand($suffixes)];
    }
}
