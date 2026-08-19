<?php

namespace App\Livewire\Handler\User;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use HandlesErrors;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public array $selected_roles = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->uncompromised()],
            'selected_roles' => 'required|array|min:1',
        ];

    }

    protected array $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'selected_roles.required' => 'Minimal pilih satu role.',
        'selected_roles.min' => 'Minimal pilih satu role.',
    ];

    public function save(): void
    {
        $this->validate();

        $this->runSafely(function () {
            DB::transaction(function () {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'is_active' => true,
                ]);

                $user->assignRole($this->selected_roles);
            });

            $this->redirect(route('users.index'), navigate: true);
            session()->flash('status', 'Berhasil menambah data user: '.$this->name);
        }, 'Gagal menambah data user', [
            'action' => 'create user',
            'email' => $this->email,
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.user.create', [
            'list_roles' => Role::orderBy('name', 'asc')->get(),
        ]);
    }
}
