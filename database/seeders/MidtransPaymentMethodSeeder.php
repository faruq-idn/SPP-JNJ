<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MidtransPaymentMethodSeeder extends Seeder
{
    public function run()
    {
        MetodePembayaran::firstOrCreate(
            ['kode' => 'MIDTRANS'],
            [
                'nama' => 'Payment Gateway',
                'status' => 'aktif'
            ]
        );
    }
}
