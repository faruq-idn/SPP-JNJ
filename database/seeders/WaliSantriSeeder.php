<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WaliSantriSeeder extends Seeder
{
    protected $namaDepan = [
        'Ahmad', 'Muhammad', 'Abdul', 'Haji', 'Siti', 
        'Nur', 'Dewi', 'Sri', 'Mega', 'Tri',
        'Agus', 'Budi', 'Cahyo', 'Dedi', 'Eko'
    ];

    protected $namaBelakang = [
        'Hidayat', 'Rahman', 'Syafii', 'Hakim', 'Wijaya',
        'Pratama', 'Putra', 'Saputra', 'Kusuma', 'Arifin',
        'Santoso', 'Wibowo', 'Susanto', 'Utama', 'Nugroho'
    ];

    public function run()
    {
        // Generate 30 wali with unique email addresses
        for ($i = 1; $i <= 30; $i++) {
            $namaDepan = $this->namaDepan[array_rand($this->namaDepan)];
            $namaBelakang = $this->namaBelakang[array_rand($this->namaBelakang)];
            $nama = $namaDepan . ' ' . $namaBelakang;
            
            // Generate unique email
            $emailBase = strtolower(str_replace(' ', '', $namaDepan)) . $i;
            $email = $emailBase . '@example.com';
            
            User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'wali'
            ]);
        }
    }
}
