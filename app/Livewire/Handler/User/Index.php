<?php

namespace App\Livewire\Handler\User;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        return view('livewire.handler.user.index');
    }
}
