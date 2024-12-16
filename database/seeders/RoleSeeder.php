<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Buat roles
        $roles = ['admin', 'petugas', 'wali'];

        foreach ($roles as $role) {
            // Buat role
            Role::create(['name' => $role]);

            // Buat user dengan role
            $user = User::create([
                'name' => ucfirst($role),
                'email' => $role.'@example.com',
                'password' => bcrypt('password'),
                'role' => $role
            ]);

            // Assign role ke user
            $user->assignRole($role);
        }
    }
}
