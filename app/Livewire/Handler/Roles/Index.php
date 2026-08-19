<?php

namespace App\Livewire\Handler\Roles;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        return view('livewire.handler.roles.index');
    }
}
