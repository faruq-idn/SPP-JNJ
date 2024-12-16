<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\KategoriSantri;
use App\Models\PembayaranSpp;
use Carbon\Carbon;

class SantriSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori Reguler
        $kategoriReguler = KategoriSantri::where('nama', 'Reguler')->first();

        // Data santri dengan tunggakan
        $santriData = [
            [
                'nisn' => '12345678',
                'nama' => 'Ahmad Faiz',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2006-05-15',
                'alamat' => 'Jl. Sumber Makmur No. 123',
                'nama_wali' => 'Abdul Malik',
                'tanggal_masuk' => '2022-07-15',
                'jenjang' => 'SMP',
                'kelas' => '8A',
                'kategori_id' => $kategoriReguler->id,
                'status' => 'aktif',
                'tunggakan' => 3 // Tunggak 3 bulan
            ],
            [
                'nisn' => '87654321',
                'nama' => 'Siti Fatimah',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2005-08-20',
                'alamat' => 'Jl. Sumber Jaya No. 45',
                'nama_wali' => 'Abdullah',
                'tanggal_masuk' => '2022-07-15',
                'jenjang' => 'SMA',
                'kelas' => '11B',
                'kategori_id' => $kategoriReguler->id,
                'status' => 'aktif',
                'tunggakan' => 2 // Tunggak 2 bulan
            ],
        ];

        foreach ($santriData as $data) {
            $tunggakan = $data['tunggakan'];
            unset($data['tunggakan']);

            // Buat data santri
            $santri = Santri::create($data);

            // Buat data pembayaran
            $now = Carbon::now();
            $nominal = $kategoriReguler->tarifTerbaru->nominal;

            // Generate pembayaran untuk 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $bulan = $now->copy()->subMonths($i);

                // Jika dalam rentang tunggakan, status pending
                $status = $i < $tunggakan ? 'pending' : 'success';

                PembayaranSpp::create([
                    'santri_id' => $santri->id,
                    'tanggal_bayar' => $status === 'success' ? $bulan : null,
                    'bulan' => $bulan->format('m'),
                    'tahun' => $bulan->format('Y'),
                    'nominal' => $nominal,
                    'metode_pembayaran' => $status === 'success' ? 'tunai' : null,
                    'status' => $status,
                    'keterangan' => $status === 'success' ? 'Lunas' : 'Belum dibayar'
                ]);
            }
        }
    }
}
