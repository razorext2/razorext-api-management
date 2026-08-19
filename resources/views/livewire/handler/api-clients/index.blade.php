{{-- Goal: API Clients Index Page with PowerGrid Table --}}
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">API Clients & Gateway</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola aplikasi klien eksternal, kunci API (API Key), dan batasan akses (Rate Limiting)</p>
        </div>

        @can('api-clients-create')
            <x-button.primary href="{{ route('api-clients.create') }}" wire:navigate>
                <x-slot name="icon">
                    <x-icons.plus class="h-4 w-4" />
                </x-slot>
                Tambah Client Baru
            </x-button.primary>
        @endcan
    </div>

    <livewire:powergrid-tables.api-client-table />
</div>
