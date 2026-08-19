{{-- Goal: User Index Page Wrapper with Header Action and PowerGrid Table --}}
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Manajemen User</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola daftar pengguna dan hak akses sistem</p>
        </div>

        @can('users-create')
            <x-button.primary href="{{ route('users.create') }}" wire:navigate>
                <x-slot name="icon">
                    <x-icons.plus class="h-4 w-4" />
                </x-slot>
                Tambah User
            </x-button.primary>
        @endcan
    </div>

    <livewire:powergrid-tables.user-table />
</div>
