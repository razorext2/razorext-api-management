<?php

namespace App\Livewire\Handler\Sandbox;

use App\Livewire\Concerns\HandlesErrors;
use App\Services\DataMining\AprioriService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AprioriSandbox extends Component
{
    use HandlesErrors;

    public string $transactions_text = '';

    public float $min_support = 0.3;

    public float $min_confidence = 0.6;

    public ?array $result = null;

    public string $selected_preset = 'retail';

    public function mount(): void
    {
        $this->loadPreset('retail');
    }

    public function loadPreset(string $preset): void
    {
        $this->selected_preset = $preset;

        if ($preset === 'retail') {
            $this->transactions_text = "Roti, Mentega, Susu\nRoti, Mentega\nSusu, Keju\nRoti, Susu, Selai\nRoti, Mentega, Susu, Selai\nKeju, Susu, Roti\nMentega, Susu";
            $this->min_support = 0.4;
            $this->min_confidence = 0.6;
        } elseif ($preset === 'cafe') {
            $this->transactions_text = "Espresso, Croissant\nAmericano, Donut\nEspresso, Croissant, Mineral Water\nLatte, Sandwich\nLatte, Croissant, Donut\nEspresso, Americano\nLatte, Croissant";
            $this->min_support = 0.3;
            $this->min_confidence = 0.5;
        } elseif ($preset === 'ecommerce') {
            $this->transactions_text = "Laptop, Mouse, Keyboard\nLaptop, Mouse\nMouse, Mousepad\nLaptop, Headset\nLaptop, Mouse, Keyboard, Mousepad\nKeyboard, Mousepad\nLaptop, Mouse, Headset";
            $this->min_support = 0.35;
            $this->min_confidence = 0.6;
        }

        $this->runCalculation();
    }

    public function runCalculation(): void
    {
        $this->runSafely(function () {
            $rawLines = array_filter(array_map('trim', explode("\n", $this->transactions_text)));
            $transactions = [];

            foreach ($rawLines as $line) {
                $items = array_values(array_filter(array_map('trim', explode(',', $line))));
                if (! empty($items)) {
                    $transactions[] = $items;
                }
            }

            if (empty($transactions)) {
                $this->result = null;

                return;
            }

            $service = app(AprioriService::class);
            $this->result = $service->calculate($transactions, $this->min_support, $this->min_confidence);
        }, 'Gagal memproses perhitungan Apriori');
    }

    public function render(): View
    {
        return view('livewire.handler.sandbox.apriori-sandbox');
    }
}
