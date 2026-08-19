<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = [
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',
            'roles-list',
            'roles-create',
            'roles-edit',
            'roles-delete',
            'permissions-list',
            'permissions-create',
            'permissions-edit',
            'permissions-delete',
            'log-list',
            'settings-manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $role->syncPermissions(Permission::all());

        $user = User::updateOrCreate(
            ['email' => 'user@email.com'],
            [
                'name' => 'Dummy User',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );

        $user->assignRole($role);
        $user->syncPermissions(Permission::all());

        $this->call(SettingSeeder::class);
    }
}
