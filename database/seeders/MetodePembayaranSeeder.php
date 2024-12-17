<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    public function run()
    {
        $metode = [
            ['nama' => 'Manual', 'kode' => 'MANUAL'],
            ['nama' => 'Transfer Bank', 'kode' => 'BANK'],
            ['nama' => 'E-Wallet', 'kode' => 'EWALLET']
        ];

        foreach ($metode as $m) {
            MetodePembayaran::create($m);
        }
    }
}
