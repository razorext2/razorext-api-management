<?php

use App\Livewire\Handler\Settings\Index;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'settings-manage']);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('settings-manage');
});

it('renders settings page for authorized users', function () {
    $this->actingAs($this->user)
        ->get(route('settings.index'))
        ->assertStatus(200)
        ->assertSee('Pengaturan Website');
});

it('updates text attributes correctly and clears cache', function () {
    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('site_name', 'RazorAPI Custom')
        ->set('site_title', 'Custom Portal System')
        ->set('sidebar_title', 'Portal HQ')
        ->call('save')
        ->assertDispatched('swal');

    expect(setting('site_name'))->toBe('RazorAPI Custom');
    expect(setting('site_title'))->toBe('Custom Portal System');
    expect(setting('sidebar_title'))->toBe('Portal HQ');
});

it('denies access to settings page for unauthorized users', function () {
    $unauthorizedUser = User::factory()->create();

    $this->actingAs($unauthorizedUser)
        ->get(route('settings.index'))
        ->assertStatus(403);
});
