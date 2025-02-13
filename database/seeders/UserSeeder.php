<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Keuangan',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas'
        ]);

        // Add additional petugas for testing
        User::create([
            'name' => 'Petugas Keuangan 2',
            'email' => 'petugas2@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas'
        ]);
    }
}
