<?php

/** Goal: Edit user account, Caller: Admin User Management, Deps: User Model, Role Model */

namespace App\Livewire\Handler\User;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use HandlesErrors;

    public User $user;

    public $name;

    public $email;

    public $is_active;

    public $deactivation_reason;

    public $password;

    public $password_confirmation;

    public $selected_roles = [];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->deactivation_reason = $user->deactivation_reason;
        $this->selected_roles = $user->roles->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->uncompromised()],
            'selected_roles' => 'required|array|min:1',
            'is_active' => 'required|boolean',
            'deactivation_reason' => 'required_if:is_active,0|nullable|string|max:255',
        ];

    }

    protected array $messages = [
        'deactivation_reason.required_if' => 'Alasan nonaktif wajib diisi jika status diatur ke Tidak Aktif.',
        'selected_roles.required' => 'Minimal pilih satu role.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function save(): mixed
    {
        $this->validate();

        return $this->runSafely(function () {
            DB::transaction(function () {
                $data = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'is_active' => $this->is_active,
                ];

                if (! $this->is_active) {
                    $data['deactivation_at'] = now();
                    $data['deactivation_reason'] = $this->deactivation_reason;

                    // Hapus session jika user dinonaktifkan
                    DB::table('sessions')->where('user_id', $this->user->id)->delete();
                } else {
                    $data['deactivation_reason'] = null;
                    $data['deactivation_at'] = null;
                }

                if (! empty($this->password)) {
                    $data['password'] = Hash::make($this->password);
                }

                $this->user->update($data);
                $this->user->syncRoles($this->selected_roles);
            });

            return redirect()->route('users.index')
                ->with('status', 'Berhasil memperbarui data user: '.$this->name);
        }, 'Gagal memperbarui data user', [
            'action' => 'update user',
            'updated_user_id' => $this->user->id,
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.user.edit', [
            'list_roles' => Role::orderBy('name', 'asc')->get(),
        ]);
    }
}
