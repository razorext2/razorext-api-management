<?php

use App\Livewire\Handler\Permissions\Update;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

test('permissions update component can mount with permission parameter', function () {
    $perm = Permission::create(['name' => 'permissions-edit']);
    $user = User::factory()->create(['is_active' => true]);
    $user->givePermissionTo($perm);

    $targetPerm = Permission::create(['name' => 'test-permission']);

    Livewire::actingAs($user)
        ->test(Update::class, ['permission' => $targetPerm])
        ->assertSet('name', 'test-permission')
        ->assertStatus(200);
});

test('permissions update route can be rendered', function () {
    $perm = Permission::create(['name' => 'permissions-edit']);
    $user = User::factory()->create(['is_active' => true]);
    $user->givePermissionTo($perm);

    $targetPerm = Permission::create(['name' => 'test-permission']);

    $response = $this
        ->actingAs($user)
        ->get(route('permissions.edit', ['permission' => $targetPerm->id]));

    $response->assertOk();
});
