<?php

namespace App\Livewire\Handler\Announcement;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        return view('livewire.handler.announcement.index');
    }
}
