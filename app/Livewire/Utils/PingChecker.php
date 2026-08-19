<?php

namespace App\Livewire\Utils;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class PingChecker extends Component
{
    public ?int $pingMs = null;

    public bool $isOnline = true;

    public function render(): View
    {
        return view('livewire.utils.ping-checker');
    }
}
