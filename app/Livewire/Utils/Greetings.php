<?php

namespace App\Livewire\Utils;

use Carbon\Carbon;
use Livewire\Component;

class Greetings extends Component
{
    public $greet;

    public $class = '';

    public function mount()
    {
        $hour = Carbon::now()->hour;

        if ($hour >= 5 && $hour <= 10) {
            $this->greet = 'Selamat pagi,';
        } elseif ($hour >= 11 && $hour <= 15) {
            $this->greet = 'Selamat siang,';
        } elseif ($hour >= 16 && $hour <= 19) {
            $this->greet = 'Selamat sore,';
        } else {
            $this->greet = 'Selamat malam,';
        }
    }

    public function render()
    {
        return view('livewire.utils.greetings');
    }
}
