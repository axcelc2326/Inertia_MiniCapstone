<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache (important when re-running)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'edit items',
            'delete items',
            'assign roles',
            'create items',
            'view items',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'create items',
            'view items',
        ]);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole($userRole);
    }
}
