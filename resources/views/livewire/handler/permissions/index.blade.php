{{-- Goal: Permissions Index Page Wrapper with Header Action and PowerGrid Table --}}
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Manajemen Permission</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftar hak akses dan izin fitur aplikasi</p>
        </div>
        @can('permissions-create')
            <x-button.primary href="{{ route('permissions.create') }}" wire:navigate>
                <x-slot name="icon">
                    <x-icons.plus class="h-4 w-4" />
                </x-slot>
                Tambah Permission
            </x-button.primary>
        @endcan
    </div>

    <livewire:powergrid-tables.permissions-table />
</div>
