<?php

namespace App\Livewire\Handler\Roles;

use App\Livewire\Concerns\HandlesErrors;
use App\Livewire\Forms\Roles\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Create extends Component
{
    use HandlesErrors;

    public Post $form;

    public array $permissions = [];

    public bool $selectAll = false;

    public string $searchPermission = '';

    public function mount(): void
    {
        // Cache semi-static permission list to avoid repeated DB queries
        $this->permissions = cache()->remember('permissions.all', now()->addMinutes(10), fn () => Permission::pluck('name', 'id')->toArray()
        );
    }

    public function toggleSelectAll(): void
    {
        // Gabungkan dua fungsi menjadi satu
        $this->form->selectedPermissions = $this->selectAll ? array_keys($this->permissions) : [];
    }

    public function updatedSelectedPermissions(): void
    {
        // Jika semua dipilih, update "Select All"
        $this->selectAll = count($this->selectedPermissions) === count($this->permissions);
    }

    public function save(): void
    {
        // Validasi form
        $this->form->validate();

        $this->runSafely(function () {
            // Panggil method store di form Post
            $this->form->store();

            // Panggil event swal, tampilkan pesan
            $this->dispatch('swal', title: 'Berhasil', text: 'Berhasil menambah data role', icon: 'success');

            // Redirect
            $this->redirect(route('roles.index'), navigate: true);
        }, 'Gagal menambah data role.', [
            'action' => 'create role',
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        $filteredPermissions = collect($this->permissions)
            ->filter(fn ($name) => empty($this->searchPermission) || str_contains(strtolower($name), strtolower($this->searchPermission)))
            ->map(fn ($name, $id) => (object) ['id' => $id, 'name' => $name]);

        $groupedPermissions = $filteredPermissions->groupBy(fn ($permission) => explode('-', $permission->name)[0]);

        return view('livewire.handler.roles.create', compact('groupedPermissions'));
    }
}
