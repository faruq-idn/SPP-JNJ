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
                'biaya_makan' => 450000,
                'biaya_asrama' => 135000,
                'biaya_listrik' => 25000,
                'biaya_kesehatan' => 25000
            ],
            [
                'nama' => 'Beasiswa',
                'keterangan' => 'Santri penerima beasiswa prestasi',
                'biaya_makan' => 225000,
                'biaya_asrama' => 70000,
                'biaya_listrik' => 15000,
                'biaya_kesehatan' => 15000
            ],
            [
                'nama' => 'Yatim/Piatu',
                'keterangan' => 'Santri yatim atau piatu',
                'biaya_makan' => 270000,
                'biaya_asrama' => 80000,
                'biaya_listrik' => 20000,
                'biaya_kesehatan' => 20000
            ],
            [
                'nama' => 'Khusus',
                'keterangan' => 'Santri program khusus/tahfidz',
                'biaya_makan' => 675000,
                'biaya_asrama' => 200000,
                'biaya_listrik' => 35000,
                'biaya_kesehatan' => 35000
            ],
        ];

        foreach ($kategori as $k) {
            $kategori = KategoriSantri::create([
                'nama' => $k['nama'],
                'keterangan' => $k['keterangan'],
                'biaya_makan' => $k['biaya_makan'],
                'biaya_asrama' => $k['biaya_asrama'],
                'biaya_listrik' => $k['biaya_listrik'],
                'biaya_kesehatan' => $k['biaya_kesehatan']
            ]);

            // Hitung total dari rincian biaya
            $total = $k['biaya_makan'] + $k['biaya_asrama'] + $k['biaya_listrik'] + $k['biaya_kesehatan'];
            
            RiwayatTarifSpp::create([
                'kategori_id' => $kategori->id,
                'biaya_makan' => $k['biaya_makan'],
                'biaya_asrama' => $k['biaya_asrama'],
                'biaya_listrik' => $k['biaya_listrik'],
                'biaya_kesehatan' => $k['biaya_kesehatan'],
                'nominal' => $total,
                'berlaku_mulai' => now(),
                'keterangan' => 'Tarif awal'
            ]);
        }
    }
}
