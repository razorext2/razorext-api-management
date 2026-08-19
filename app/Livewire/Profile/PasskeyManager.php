<?php

namespace App\Livewire\Profile;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\WebAuthnCredential;
use App\Services\WebAuthnService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PasskeyManager extends Component
{
    use HandlesErrors;

    #[Validate('required|string|min:3|max:50')]
    public string $nickname = '';

    public bool $showRegisterModal = false;

    public function openRegisterModal(): void
    {
        $this->reset(['nickname']);
        $this->resetValidation();
        $this->showRegisterModal = true;
    }

    public function getRegistrationOptions(WebAuthnService $service): array
    {
        $this->validateOnly('nickname');

        $user = auth()->user();

        return $service->getRegistrationArgs($user);
    }

    public function savePasskey(string $clientDataJSON, string $attestationObject, WebAuthnService $service): void
    {
        $this->validate();

        $this->runSafely(function () use ($clientDataJSON, $attestationObject, $service) {
            $user = auth()->user();

            $service->processRegistration($user, $this->nickname, $clientDataJSON, $attestationObject);

            $this->showRegisterModal = false;
            $this->reset(['nickname']);

            $this->dispatch('swal', title: 'Berhasil!', text: 'Passkey / Biometrik berhasil ditambahkan.', icon: 'success');
        }, 'Gagal mendaftarkan Passkey.', [
            'user_id' => auth()->id(),
            'action' => 'register passkey',
        ]);
    }

    public function deletePasskey(int $id): void
    {
        $this->runSafely(function () use ($id) {
            $credential = WebAuthnCredential::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
            $credential->delete();

            $this->dispatch('swal', title: 'Berhasil!', text: 'Passkey berhasil dihapus.', icon: 'success');
        }, 'Gagal menghapus Passkey.', [
            'user_id' => auth()->id(),
            'action' => 'delete passkey',
        ]);
    }

    public function render(): View
    {
        return view('livewire.profile.passkey-manager', [
            'passkeys' => WebAuthnCredential::where('user_id', auth()->id())->latest()->get(),
        ]);
    }
}
