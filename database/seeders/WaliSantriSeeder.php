<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\PembayaranSpp;
use App\Models\MetodePembayaran;
use Carbon\Carbon;

class WaliSantriSeeder extends Seeder
{
    public function run(): void
    {
        // Data wali santri
        $waliData = [
            ['name' => 'Abdul Malik', 'email' => 'malik@example.com'],
            ['name' => 'Siti Aminah', 'email' => 'aminah@example.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'fauzi@example.com'],
            ['name' => 'Nur Hidayah', 'email' => 'hidayah@example.com'],
            ['name' => 'Muhammad Yusuf', 'email' => 'yusuf@example.com'],
        ];

        // Buat user wali
        foreach ($waliData as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'wali'
            ])->assignRole('wali');
        }

        // Ambil semua wali dan kategori
        $walis = User::where('role', 'wali')->get();
        $kategoriReguler = KategoriSantri::where('nama', 'Reguler')->first();
        $kategoriYatim = KategoriSantri::where('nama', 'Yatim/Piatu')->first();
        $kategoriBeasiswa = KategoriSantri::where('nama', 'Beasiswa')->first();
        $kategoriKhusus = KategoriSantri::where('nama', 'Khusus')->first();

        // Data santri dengan variasi tunggakan
        $santriData = [
            // Santri Reguler dengan tunggakan 3 bulan
            [
                'nisn' => '12345671',
                'nama' => 'Ahmad Faiz',
                'wali_id' => $walis[0]->id,
                'jenjang' => 'SMP',
                'kelas' => '7A',
                'kategori_id' => $kategoriReguler->id,
                'tunggakan' => 3
            ],
            // Santri Reguler dengan tunggakan 2 bulan
            [
                'nisn' => '12345672',
                'nama' => 'Siti Fatimah',
                'wali_id' => $walis[0]->id,
                'jenjang' => 'SMP',
                'kelas' => '7A',
                'kategori_id' => $kategoriReguler->id,
                'tunggakan' => 2
            ],
            // Santri Yatim/Piatu
            [
                'nisn' => '12345673',
                'nama' => 'Muhammad Rizki',
                'wali_id' => $walis[1]->id,
                'jenjang' => 'SMP',
                'kelas' => '8B',
                'kategori_id' => $kategoriYatim->id,
                'tunggakan' => 1
            ],
            // Santri Beasiswa
            [
                'nisn' => '12345674',
                'nama' => 'Zahra Putri',
                'wali_id' => $walis[1]->id,
                'jenjang' => 'SMP',
                'kelas' => '9A',
                'kategori_id' => $kategoriBeasiswa->id,
                'tunggakan' => 0
            ],
            // Santri Khusus
            [
                'nisn' => '12345675',
                'nama' => 'Abdul Rahman',
                'wali_id' => $walis[2]->id,
                'jenjang' => 'SMA',
                'kelas' => '10A',
                'kategori_id' => $kategoriKhusus->id,
                'tunggakan' => 0
            ],
            // Santri Reguler SMA
            [
                'nisn' => '12345676',
                'nama' => 'Nur Aini',
                'wali_id' => $walis[2]->id,
                'jenjang' => 'SMA',
                'kelas' => '10B',
                'kategori_id' => $kategoriReguler->id,
                'tunggakan' => 4
            ],
            // Santri Yatim/Piatu SMA
            [
                'nisn' => '12345677',
                'nama' => 'Hasan Basri',
                'wali_id' => $walis[3]->id,
                'jenjang' => 'SMA',
                'kelas' => '11A',
                'kategori_id' => $kategoriYatim->id,
                'tunggakan' => 0
            ],
            // Santri Beasiswa SMA
            [
                'nisn' => '12345678',
                'nama' => 'Dewi Safitri',
                'wali_id' => $walis[3]->id,
                'jenjang' => 'SMA',
                'kelas' => '11B',
                'kategori_id' => $kategoriBeasiswa->id,
                'tunggakan' => 0
            ],
            // Santri Khusus SMA
            [
                'nisn' => '12345679',
                'nama' => 'Rizky Maulana',
                'wali_id' => $walis[4]->id,
                'jenjang' => 'SMA',
                'kelas' => '12A',
                'kategori_id' => $kategoriKhusus->id,
                'tunggakan' => 1
            ],
            // Santri Reguler SMP dengan tunggakan
            [
                'nisn' => '12345680',
                'nama' => 'Putri Rahmawati',
                'wali_id' => $walis[4]->id,
                'jenjang' => 'SMP',
                'kelas' => '8A',
                'kategori_id' => $kategoriReguler->id,
                'tunggakan' => 5
            ],
        ];

        // Generate data santri dan pembayaran
        foreach ($santriData as $data) {
            $tunggakan = $data['tunggakan'];
            unset($data['tunggakan']);

            // Data default santri
            $santri = Santri::create(array_merge($data, [
                'jenis_kelamin' => rand(0,1) ? 'L' : 'P',
                'tanggal_lahir' => Carbon::now()->subYears(rand(12,18)),
                'alamat' => 'Jl. Contoh No. ' . rand(1,100),
                'tanggal_masuk' => Carbon::now()->subMonths(rand(1,24)),
                'status' => 'aktif',
                // Status SPP akan otomatis default ke 'Belum Lunas'
            ]));

            // Generate pembayaran 12 bulan terakhir
            $now = Carbon::now();
            $nominal = $santri->kategori->tarifTerbaru->nominal;

            for ($i = 11; $i >= 0; $i--) {
                $bulan = $now->copy()->subMonths($i);
                $status = $i < $tunggakan ? 'pending' : 'success';

                // Untuk pembayaran yang success, gunakan metode Manual/Tunai
                $metodeTunai = MetodePembayaran::where('kode', 'MANUAL_TUNAI')->first();
                $transaction_id = $status === 'success' ? 'MANUAL-'.$santri->id.'-'.$i.'-'.time() : null;
                $order_id = $status === 'success' ? 'MANUAL-'.$santri->id.'-'.$i.'-'.time() : null;

                PembayaranSpp::create([
                    'santri_id' => $santri->id,
                    'tanggal_bayar' => $status === 'success' ? $bulan : null,
                    'bulan' => $bulan->format('m'),
                    'tahun' => $bulan->format('Y'),
                    'nominal' => $nominal,
                    'status' => $status,
                    'keterangan' => $status === 'success' ? 'Pembayaran ' . $metodeTunai->nama : 'Belum dibayar',
                    'metode_pembayaran_id' => $status === 'success' ? $metodeTunai->id : null,
                    'payment_type' => $status === 'success' ? $metodeTunai->nama : null,
                    'transaction_id' => $transaction_id,
                    'order_id' => $order_id
                ]);
            }
        }

        // Contoh pembayaran SPP online
        $metodeOnline = MetodePembayaran::where('kode', 'MIDTRANS')->first();
        if ($metodeOnline) {
            PembayaranSpp::create([
                'santri_id' => 1,
                'tanggal_bayar' => '2024-01-20 07:00:32',
                'bulan' => '01',
                'tahun' => '2024',
                'nominal' => 500000.00,
                'status' => 'success',
                'keterangan' => 'Pembayaran via ' . $metodeOnline->nama,
                'metode_pembayaran_id' => $metodeOnline->id,
                'payment_type' => $metodeOnline->nama,
                'transaction_id' => 'TRX-'.time(),
                'order_id' => 'SPP-1-'.time()
            ]);
        }
    }
}
