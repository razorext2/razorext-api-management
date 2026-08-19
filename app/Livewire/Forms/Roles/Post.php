<?php

namespace App\Livewire\Forms\Roles;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Post extends Form
{
    #[Validate('required|min:3')]
    public string $name = '';

    #[Validate('required')]
    public array $selectedPermissions = [];

    public function store(): void
    {
        try {
            DB::beginTransaction();

            // Buat role baru
            $role = Role::create(['name' => $this->name]);

            // Pastikan permission yang dikirim benar
            $validPermissions = Permission::whereIn('id', $this->selectedPermissions)->pluck('id')->toArray();

            // Sync permission
            $role->syncPermissions($validPermissions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($role): void
    {
        try {
            DB::beginTransaction();

            // simpan role name
            $role->name = $this->name;
            $role->save();

            // pastikan permission yang dikirim benar
            $validPermissions = Permission::whereIn('id', $this->selectedPermissions)->pluck('id')->toArray();

            // sync permission
            $role->syncPermissions($validPermissions);

            // commit perubahan ke database
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
