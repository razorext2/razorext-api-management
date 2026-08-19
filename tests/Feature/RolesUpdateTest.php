<?php

use App\Livewire\Handler\Roles\Update;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('roles update component can mount with role parameter', function () {
    $permission = Permission::create(['name' => 'roles-edit']);
    $user = User::factory()->create(['is_active' => true]);
    $user->givePermissionTo($permission);

    $role = Role::create(['name' => 'Test Role']);

    Livewire::actingAs($user)
        ->test(Update::class, ['role' => $role])
        ->assertSet('form.name', 'Test Role')
        ->assertStatus(200);
});

test('roles update route can be rendered', function () {
    $permission = Permission::create(['name' => 'roles-edit']);
    $user = User::factory()->create(['is_active' => true]);
    $user->givePermissionTo($permission);

    $role = Role::create(['name' => 'Test Role']);

    $response = $this
        ->actingAs($user)
        ->get(route('roles.edit', ['role' => $role->id]));

    $response->assertOk();
});
