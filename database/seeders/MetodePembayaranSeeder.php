<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    public function run()
    {
        $metode = [
            ['id' => 1, 'nama' => 'Manual/Tunai', 'kode' => 'MANUAL', 'status' => 'aktif'],
            ['id' => 2, 'nama' => 'Transfer Bank', 'kode' => 'BANK', 'status' => 'aktif']
        ];

        foreach ($metode as $m) {
            MetodePembayaran::create($m);
        }
    }
}
