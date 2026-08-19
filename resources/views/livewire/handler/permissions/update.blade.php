{{-- Goal: Edit details of a permission and view its assigned roles, Livewire: Handler.Permissions.Update, Alpine: None --}}
<div class="w-full space-y-4">
    <!-- Top Header Navigation -->
    <div class="rounded-xl border border-zinc-200 p-6 shadow-2xl transition-all duration-500 ease-in-out dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="flex items-center gap-4">
            <x-button.danger id="back-btn" wire:navigate href="{{ route('permissions.index') }}">
                <x-icons.angle-left class="h-5 w-5" />
            </x-button.danger>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                    Edit Data Perizinan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pembaruan informasi perizinan untuk
                    <span class="font-bold text-blue-600 dark:text-blue-400">{{ $permission?->name }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Form -->
    <form wire:submit.prevent="save" class="space-y-4">
        @csrf
        @method('put')
        <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-8 flex items-center gap-3">
                <div class="h-10 w-1 rounded-full bg-blue-600"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Informasi Perizinan</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="flex w-full flex-col">
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="guard_name">
                        Guard Name
                    </label>
                    <x-input.basic class="cursor-not-allowed" id="guard_name" wire:model="guard_name" name="guard_name" readonly />
                </div>
                <div class="flex w-full flex-col">
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="name">
                        Nama Perizinan
                    </label>
                    <x-input.basic id="name" wire:model.blur="name" name="name" placeholder="Isi dengan nama perizinan"
                        required="" />
                    @error('name')
                        <span class="error mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex w-full flex-col">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Daftar Role Terkait
                </label>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @forelse ($permission->roles as $r)
                        <span class="rounded-lg bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            {{ $r->name }}
                        </span>
                    @empty
                        <span class="text-sm italic text-zinc-500 dark:text-zinc-400">
                            Belum ada role yang memiliki perizinan ini.
                        </span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-button.primary id="store" type="submit" wire:loading.attr="disabled" wire:target="save">
                <x-slot name="icon">
                    <x-icons.checklist-stepper wire:loading.remove wire:target="save" class="h-5 w-5" />
                    <x-icons.loading wire:loading wire:target="save" class="h-4 w-4 animate-spin" />
                </x-slot>

                <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                <span wire:loading wire:target="save">Memproses...</span>
            </x-button.primary>

            <x-button.secondary href="{{ route('permissions.index') }}" wire:navigate>
                Batal
            </x-button.secondary>
        </div>
    </form>
</div>
