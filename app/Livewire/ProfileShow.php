<?php

/** Goal: Full Page Livewire component for Profile View (me), Caller: routes/web.php (profile.me), Livewire: Yes */

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileShow extends Component
{
    public function render(): View
    {
        return view('livewire.profile-show');
    }
}
