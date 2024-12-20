<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    public function run()
    {
        $metode = [
            [
                'kode' => 'MANUAL',
                'nama' => 'Manual/Tunai',
                'status' => 'aktif'
            ],
            [
                'kode' => 'MIDTRANS',
                'nama' => 'Payment Gateway',
                'status' => 'aktif'
            ]
        ];

        foreach ($metode as $m) {
            MetodePembayaran::firstOrCreate(
                ['kode' => $m['kode']],
                $m
            );
        }
    }
}
