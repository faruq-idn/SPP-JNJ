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
                'kode' => 'MANUAL_TUNAI',
                'nama' => 'Manual/Tunai',
                'status' => 'aktif'
            ],
            [
                'kode' => 'MANUAL_TRANSFER',
                'nama' => 'Manual/Transfer',
                'status' => 'aktif'
            ],
            [
                'kode' => 'MIDTRANS',
                'nama' => 'Pembayaran Online',
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
