<?php

namespace App\Livewire\Handler\ApiClients;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\ApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    use HandlesErrors;

    public string $name = '';
    public ?string $description = null;
    public int $rate_limit_per_minute = 60;
    public bool $is_active = true;
    public ?string $allowed_ips_text = null;
    public string $generated_key = '';
    public string $generated_secret = '';

    public function mount(): void
    {
        $this->generated_key = ApiClient::generateKey();
        $this->generated_secret = ApiClient::generateSecret();
    }

    public function generateNewKey(): void
    {
        $this->generated_key = ApiClient::generateKey();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'rate_limit_per_minute' => 'required|integer|min:1|max:10000',
            'is_active' => 'boolean',
            'allowed_ips_text' => 'nullable|string',
        ];
    }

    protected array $messages = [
        'name.required' => 'Nama aplikasi client wajib diisi.',
        'rate_limit_per_minute.required' => 'Rate limit wajib diisi.',
        'rate_limit_per_minute.min' => 'Rate limit minimal 1 request/menit.',
    ];

    public function save(): void
    {
        $this->validate();

        $this->runSafely(function () {
            $allowedIps = null;
            if (! empty($this->allowed_ips_text)) {
                $allowedIps = array_values(array_filter(array_map('trim', explode(',', $this->allowed_ips_text))));
            }

            $slug = Str::slug($this->name);
            $baseSlug = $slug;
            $count = 1;
            while (ApiClient::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            ApiClient::create([
                'name' => $this->name,
                'slug' => $slug,
                'api_key' => $this->generated_key,
                'secret_key' => $this->generated_secret,
                'description' => $this->description,
                'rate_limit_per_minute' => $this->rate_limit_per_minute,
                'is_active' => $this->is_active,
                'allowed_ips' => $allowedIps,
            ]);

            session()->flash('status', "Aplikasi client '{$this->name}' berhasil didaftarkan.");
            $this->redirect(route('api-clients.index'), navigate: true);
        }, 'Gagal mendaftarkan API client', [
            'name' => $this->name,
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.api-clients.create');
    }
}
