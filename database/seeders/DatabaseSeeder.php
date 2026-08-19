<?php

namespace Database\Seeders;

use App\Models\ApiClient;
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
            'api-clients-list',
            'api-clients-create',
            'api-clients-edit',
            'api-clients-delete',
            'sandbox-access',
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

        // Default Demo API Client for Testing
        ApiClient::firstOrCreate(
            ['slug' => 'apriori-web-app'],
            [
                'name' => 'Aplikasi Web Apriori Client',
                'api_key' => 'apm_live_apriori_web_client_key_2026',
                'secret_key' => ApiClient::generateSecret(),
                'description' => 'Default demo client for Apriori data mining web app',
                'rate_limit_per_minute' => 120,
                'is_active' => true,
            ]
        );

        $this->call(SettingSeeder::class);
    }
}
