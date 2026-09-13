<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get Super Admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super-Admin',
            'guard_name' => 'web',
        ]);

        // Get all permissions
        $permissions = Permission::all();

        // Give all permissions to Super Admin role
        $superAdminRole->syncPermissions($permissions);

        // Create or get Super Admin user
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'super-admin',
                'password' => Hash::make('123456'),
            ]
        );

        // Assign Super Admin role
        $admin->syncRoles([$superAdminRole]);
    }
}
