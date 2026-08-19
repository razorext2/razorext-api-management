<?php

/** Goal: Main Dashboard Livewire Page Component, Caller: routes/web.php (dashboard), Livewire: Yes */

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
