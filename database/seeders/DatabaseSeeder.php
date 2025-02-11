<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            KategoriSantriSeeder::class,
            MetodePembayaranSeeder::class, // Pindahkan ke atas sebelum WaliSantriSeeder
            WaliSantriSeeder::class,
        ]);
    }
}
