<?php

namespace App\Livewire\Forms\Permissions;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission;

/**
 * The Post class is used to validate the permission form.
 *
 * This class is a Livewire Form and is used to validate the permission form.
 *
 * The name property is validated as required, string, max 20 characters, and min 5 characters.
 *
 * The store and update methods are empty and should be implemented in the child class.
 */
class Post extends Form
{
    #[Validate([
        'name.*' => 'required|min:5|max:32',
    ], message: [
        'required' => 'Field :attribute wajib diisi',
        'min' => 'Field :attribute minimal 5 karakter',
        'max' => 'Field :attribute terlalu panjang',
    ], attribute: [
        'name.*' => 'name',
    ])]
    public array $name = [0 => null];

    public function store(): void
    {
        // Let any exception bubble up to HandlesErrors::runSafely() in the caller
        foreach ($this->name as $name) {
            Permission::create(['name' => $name]);
        }
    }
}
