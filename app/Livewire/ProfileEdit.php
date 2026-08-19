<?php

/** Goal: Full Page Livewire component for Profile Edit, Caller: routes/web.php (profile.edit), Livewire: Yes */

namespace App\Livewire;

use App\Livewire\Concerns\HandlesErrors;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProfileEdit extends Component
{
    use HandlesErrors;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function updateProfileInformation(): void
    {
        $this->validateOnly('name');
        $this->validateOnly('email');

        $this->runSafely(function () {
            $user = auth()->user();
            if (! $user) {
                return;
            }

            if ($this->email !== $user->email) {
                $this->validate(['email' => 'unique:users,email,'.$user->id]);
                $user->email_verified_at = null;
            }

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            session()->flash('status', 'Data profil berhasil diperbarui.');
        }, 'Gagal memperbarui profil.', [
            'user_id' => auth()->id(),
            'action' => 'update profile information',
        ]);
    }

    public function updatePassword(): void
    {
        $this->validate([

            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->uncompromised()],
        ]);

        $this->runSafely(function () {
            $user = auth()->user();
            if ($user) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);

                $this->reset(['current_password', 'password', 'password_confirmation']);
                session()->flash('status', 'Password berhasil diperbarui.');
            }
        }, 'Gagal memperbarui password.', [
            'user_id' => auth()->id(),
            'action' => 'update password',
        ]);
    }

    public function render(): View
    {
        return view('livewire.profile-edit', [
            'user' => auth()->user(),
        ]);
    }
}
