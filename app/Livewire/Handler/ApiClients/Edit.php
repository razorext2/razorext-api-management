<?php

namespace App\Livewire\Handler\ApiClients;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\ApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    use HandlesErrors;

    public ApiClient $client;

    public string $name = '';

    public ?string $description = null;

    public int $rate_limit_per_minute = 60;

    public bool $is_active = true;

    public ?string $allowed_ips_text = null;

    public string $api_key = '';

    public bool $key_changed = false;

    public function mount(ApiClient $client): void
    {
        $this->client = $client;
        $this->name = $client->name;
        $this->description = $client->description;
        $this->rate_limit_per_minute = $client->rate_limit_per_minute;
        $this->is_active = (bool) $client->is_active;
        $this->allowed_ips_text = ! empty($client->allowed_ips) ? implode(', ', $client->allowed_ips) : null;
        $this->api_key = $client->api_key;
    }

    public function regenerateKey(): void
    {
        $this->api_key = ApiClient::generateKey();
        $this->key_changed = true;

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Key Baru Di-generate',
            'text' => 'Key baru telah dibuat. Klik "Simpan Perubahan" di bawah untuk mengaktifkannya di database.',
        ]);
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

    public function save(): void
    {
        $this->validate();

        $this->runSafely(function () {
            $allowedIps = null;
            if (! empty($this->allowed_ips_text)) {
                $allowedIps = array_values(array_filter(array_map('trim', explode(',', $this->allowed_ips_text))));
            }

            $this->client->update([
                'name' => $this->name,
                'api_key' => $this->api_key,
                'description' => $this->description,
                'rate_limit_per_minute' => $this->rate_limit_per_minute,
                'is_active' => $this->is_active,
                'allowed_ips' => $allowedIps,
            ]);

            session()->flash('status', "Perubahan aplikasi client '{$this->name}' berhasil disimpan.");
            $this->redirect(route('api-clients.index'), navigate: true);
        }, 'Gagal memperbarui API client', [
            'client_id' => $this->client->id,
            'user_id' => Auth::id(),
        ]);
    }

    public function deleteClient(): void
    {
        $this->runSafely(function () {
            $name = $this->client->name;
            $this->client->delete();

            session()->flash('status', "Aplikasi client '{$name}' berhasil dihapus.");
            $this->redirect(route('api-clients.index'), navigate: true);
        }, 'Gagal menghapus API client', [
            'client_id' => $this->client->id,
            'user_id' => Auth::id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.api-clients.edit');
    }
}
