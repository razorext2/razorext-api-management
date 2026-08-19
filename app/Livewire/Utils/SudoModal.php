<?php

namespace App\Livewire\Utils;

use App\Livewire\Concerns\HandlesErrors;
use App\Livewire\Concerns\RequiresSudoMode;
use App\Services\WebAuthnService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class SudoModal extends Component
{
    use HandlesErrors, RequiresSudoMode;

    public bool $showModal = false;

    public string $password = '';

    public ?string $targetEventName = null;

    public mixed $targetEventParams = null;

    #[On('openSudoModal')]
    public function openSudoModal(string $targetEventName, mixed $targetEventParams = null): void
    {
        $this->targetEventName = $targetEventName;
        $this->targetEventParams = $targetEventParams;
        $this->reset(['password']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function verifyPassword(): void
    {
        $this->validate([
            'password' => 'required',
        ]);

        if (! $this->confirmSudoPassword($this->password)) {
            $this->addError('password', 'Password yang Anda masukkan salah.');

            return;
        }

        $this->completeSudoVerification();
    }

    public function getPasskeyOptions(WebAuthnService $service): array
    {
        return $service->getLoginArgs(auth()->user());
    }

    public function verifyPasskey(string $clientDataJSON, string $authenticatorData, string $signature, string $id, WebAuthnService $service): void
    {
        if ($this->confirmSudoPasskey($clientDataJSON, $authenticatorData, $signature, $id, $service)) {
            $this->completeSudoVerification();
        } else {
            $this->dispatch('swal', title: 'Gagal', text: 'Verifikasi Passkey / Biometrik gagal.', icon: 'error');
        }
    }

    protected function completeSudoVerification(): void
    {
        $this->showModal = false;

        if ($this->targetEventName) {
            $this->dispatch($this->targetEventName, $this->targetEventParams);
        }

        $this->dispatch('swal', title: 'Mode Sudo Dikonfirmasi', text: 'Verifikasi berhasil. Aksi dilanjutkan.', icon: 'success');
    }

    public function render(): View
    {
        return view('livewire.utils.sudo-modal', [
            'hasPasskeys' => auth()->check() && auth()->user()->webauthnCredentials()->exists(),
        ]);
    }
}
