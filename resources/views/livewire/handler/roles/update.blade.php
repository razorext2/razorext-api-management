{{-- Goal: Edit role name and permissions, Livewire: Handler\Roles\Update, Alpine: search --}}
<div class="w-full space-y-4">
    <!-- Top Header Navigation -->
    <div class="rounded-xl border border-zinc-200 p-6 shadow-2xl transition-all duration-500 ease-in-out dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="flex items-center gap-4">
            <x-button.danger id="back-btn" wire:navigate href="{{ route('roles.index') }}">
                <x-icons.angle-left class="h-5 w-5" />
            </x-button.danger>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                    Edit Data Role
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pembaruan informasi role dan perizinan hak akses untuk
                    <span class="font-bold text-blue-600 dark:text-blue-400">{{ $role?->name }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Form -->
    <form wire:submit.prevent="save" class="space-y-4">
        @csrf
        <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-8 flex items-center gap-3">
                <div class="h-10 w-1 rounded-full bg-blue-600"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Informasi Role</h3>
            </div>

            <div class="w-full">
                <x-input.basic name="role_name" id="role_name" placeholder="Isi dengan nama role" wire:model="form.name"
                    required>
                    Nama Role
                </x-input.basic>
                @error('form.name')
                    <span class="error mt-2 block text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-purple-600"></div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Permissions & Hak Akses</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tentukan perizinan fitur yang dimiliki oleh role ini</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="w-full md:w-64">
                        <x-input.basic name="searchPermission" id="searchPermission" placeholder="Cari perizinan..."
                            wire:model.live="searchPermission" />
                    </div>

                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-200 px-4 py-2 text-sm font-medium text-gray-700 dark:border-zinc-800 dark:text-gray-300">
                        <input
                            class="h-4 w-4 rounded border-zinc-200 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-zinc-800 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                            id="select-all" type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                        <span>Select All</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($groupedPermissions as $group => $perms)
                    <div class="rounded-xl border border-zinc-200 p-4 transition-colors dark:border-zinc-800 dark:bg-gray-800/40"
                        x-bind:class="dynamicBg ?
                            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                        <h4 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">
                            <span class="h-1 w-4 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                            {{ $group }}
                        </h4>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                            @foreach ($perms as $permission)
                                <div class="group/perm flex items-center space-x-2 transition-all duration-200 hover:translate-x-1">
                                    <input
                                        class="permission-checkbox h-4 w-4 rounded border-zinc-200 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-zinc-800 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                        id="permission[{{ $permission->id }}]"
                                        name="permission[{{ $permission->id }}]" type="checkbox"
                                        value="{{ $permission->id }}" wire:model="form.selectedPermissions">
                                    <label
                                        class="cursor-pointer text-sm font-medium text-gray-700 transition-colors group-hover/perm:text-blue-600 dark:text-gray-300 dark:group-hover/perm:text-blue-400"
                                        for="permission[{{ $permission->id }}]">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 p-8 dark:border-zinc-800">
                        <x-icons.search class="mb-3 h-10 w-10 text-gray-400" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada perizinan yang ditemukan dengan kata kunci "{{ $searchPermission }}"</p>
                    </div>
                @endforelse
            </div>

            @error('form.selectedPermissions')
                <span class="error mt-2 block text-sm text-red-500">{{ $message }}</span>
            @enderror
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

            <x-button.secondary href="{{ route('roles.index') }}" wire:navigate>
                Batal
            </x-button.secondary>
        </div>
    </form>
</div>
