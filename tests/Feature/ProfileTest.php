<?php

use App\Livewire\ProfileEdit;
use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $user = User::factory()->create(['is_active' => true]);

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user);

    Livewire::test(ProfileEdit::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user);

    Livewire::test(ProfileEdit::class)
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $this->assertNotNull($user->refresh()->email_verified_at);
});
