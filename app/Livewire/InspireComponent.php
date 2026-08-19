<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Inspiring;
use Livewire\Component;

class InspireComponent extends Component
{
    public string $quote = '';

    public function mount(): void
    {
        $this->quote = Inspiring::quotes()->random();
    }

    public function render(): View
    {
        return view('livewire.inspire-component');
    }
}
