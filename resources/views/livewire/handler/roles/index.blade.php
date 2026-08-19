{{-- Goal: Roles Index Page Wrapper with Header Action and PowerGrid Table --}}
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Manajemen Role</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola role dan hak akses pengguna</p>
        </div>
        @can('roles-create')
            <x-button.primary href="{{ route('roles.create') }}" wire:navigate>
                <x-slot name="icon">
                    <x-icons.plus class="h-4 w-4" />
                </x-slot>
                Tambah Role
            </x-button.primary>
        @endcan
    </div>

    <livewire:powergrid-tables.roles-table />
</div>
