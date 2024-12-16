<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles if not exists
        $roles = ['admin', 'petugas', 'wali'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Sync users with roles
        $users = User::all();
        foreach ($users as $user) {
            // Remove existing roles
            $user->syncRoles([]);
            // Assign new role based on role field
            $user->assignRole($user->role);
        }
    }
}
