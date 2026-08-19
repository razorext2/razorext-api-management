<?php

use App\Livewire\Concerns\RequiresSudoMode;
use App\Livewire\Profile\PasskeyManager;
use App\Livewire\Profile\SessionManager;
use App\Models\User;
use App\Models\WebAuthnCredential;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Livewire;

test('session manager lists active database sessions', function () {
    config(['session.driver' => 'database']);

    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'session-123',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'payload' => 'payload',
        'last_activity' => now()->timestamp,
    ]);

    $this->actingAs($user);

    Livewire::test(SessionManager::class)
        ->assertStatus(200)
        ->assertSee('127.0.0.1');
});

test('user can delete other sessions with valid password', function () {
    config(['session.driver' => 'database']);

    $user = User::factory()->create([

        'password' => bcrypt('password123'),
    ]);

    $currentSessionId = session()->getId();

    DB::table('sessions')->insert([
        [
            'id' => $currentSessionId,
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ],
        [
            'id' => 'other-session-id',
            'user_id' => $user->id,
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ],
    ]);

    $this->actingAs($user);

    Livewire::test(SessionManager::class)
        ->set('password', 'password123')
        ->call('logoutOtherSessions')
        ->assertDispatched('swal');

    expect(DB::table('sessions')->where('id', 'other-session-id')->exists())->toBeFalse();
    expect(DB::table('sessions')->where('id', $currentSessionId)->exists())->toBeTrue();

});

test('passkey manager deletes credential', function () {
    $user = User::factory()->create();
    $cred = WebAuthnCredential::create([
        'user_id' => $user->id,
        'name' => 'MacBook Touch ID',
        'credential_id' => 'test-cred-id',
        'public_key' => 'test-public-key',
        'attestation_format' => 'none',
        'sign_count' => 0,
    ]);

    $this->actingAs($user);

    Livewire::test(PasskeyManager::class)
        ->call('deletePasskey', $cred->id)
        ->assertDispatched('swal');

    expect(WebAuthnCredential::find($cred->id))->toBeNull();
});

test('sudo mode trait checks password confirmation timestamp', function () {
    $user = User::factory()->create([
        'password' => bcrypt('secret123'),
    ]);

    $this->actingAs($user);

    $component = new class extends Component
    {
        use RequiresSudoMode;
    };

    expect($component->isSudoConfirmed())->toBeFalse();

    expect($component->confirmSudoPassword('secret123'))->toBeTrue();

    expect($component->isSudoConfirmed())->toBeTrue();
});
