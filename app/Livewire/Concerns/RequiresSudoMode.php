<?php

namespace App\Livewire\Concerns;

use App\Services\WebAuthnService;
use Illuminate\Support\Facades\Hash;
use Throwable;

trait RequiresSudoMode
{
    public function isSudoConfirmed(): bool
    {
        $confirmedAt = session('auth.sudo_confirmed_at');

        if (! $confirmedAt) {
            return false;
        }

        // Valid for 15 minutes (900 seconds)
        return (now()->timestamp - $confirmedAt) < 900;
    }

    public function confirmSudoPassword(string $password): bool
    {
        $user = auth()->user();

        if ($user && Hash::check($password, $user->password)) {
            session(['auth.sudo_confirmed_at' => now()->timestamp]);

            return true;
        }

        return false;
    }

    public function confirmSudoPasskey(string $clientDataJSON, string $authenticatorData, string $signature, string $credentialId, WebAuthnService $service): bool
    {
        try {
            $user = $service->processLogin($clientDataJSON, $authenticatorData, $signature, $credentialId);

            if ($user && $user->id === auth()->id()) {
                session(['auth.sudo_confirmed_at' => now()->timestamp]);

                return true;
            }
        } catch (Throwable $e) {
            return false;
        }

        return false;
    }
}
