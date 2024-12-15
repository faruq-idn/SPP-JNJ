<?php

namespace Database\Seeders;

use App\Models\KategoriSantri;
use App\Models\RiwayatTarifSpp;
use Illuminate\Database\Seeder;

class KategoriSantriSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            [
                'nama' => 'Reguler',
                'keterangan' => 'Santri dengan biaya normal',
                'tarif' => 500000
            ],
            [
                'nama' => 'Beasiswa',
                'keterangan' => 'Santri penerima beasiswa prestasi',
                'tarif' => 250000
            ],
            [
                'nama' => 'Yatim/Piatu',
                'keterangan' => 'Santri yatim atau piatu',
                'tarif' => 300000
            ],
            [
                'nama' => 'Khusus',
                'keterangan' => 'Santri program khusus/tahfidz',
                'tarif' => 750000
            ],
        ];

        foreach ($kategori as $k) {
            $kategori = KategoriSantri::create([
                'nama' => $k['nama'],
                'keterangan' => $k['keterangan']
            ]);

            RiwayatTarifSpp::create([
                'kategori_id' => $kategori->id,
                'nominal' => $k['tarif'],
                'berlaku_mulai' => now(),
                'keterangan' => 'Tarif awal'
            ]);
        }
    }
}
