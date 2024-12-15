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

        // Wali Santri
        $waliSantri = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'fauzi@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali'
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'aminah@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali'
            ],
            [
                'name' => 'Muhammad Hasan',
                'email' => 'hasan@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali'
            ],
            [
                'name' => 'Nur Fatimah',
                'email' => 'fatimah@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali'
            ],
            [
                'name' => 'Abdullah',
                'email' => 'abdullah@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali'
            ]
        ];

        foreach ($waliSantri as $wali) {
            User::create($wali);
        }
    }
}
