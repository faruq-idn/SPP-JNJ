<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            WaliSantriSeeder::class,
            KategoriSantriSeeder::class,
            MetodePembayaranSeeder::class,
            SantriSeeder::class,      // Harus setelah KategoriSantri dan WaliSantri
            PembayaranSppSeeder::class // Harus paling terakhir
        ]);
    }
}
