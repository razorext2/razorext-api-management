{{-- Goal: Edit form for user account data, Livewire: Handler\User\Edit, Alpine: - --}}
<div class="w-full space-y-6">
    <!-- Top Header Navigation -->
    <div class="rounded-xl border border-zinc-200 p-6 shadow-2xl transition-all duration-500 ease-in-out dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div class="flex items-center gap-4">
                <x-button.danger id="back-btn" wire:navigate href="{{ route('users.index') }}">
                    <x-icons.angle-left class="h-5 w-5" />
                </x-button.danger>
                <div>
                    <h2 class="flex items-center gap-2 text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                        Edit Data User
                        <x-dashboard.badge-inactive :is_active="$is_active ?? true" />
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pembaruan informasi profil dan hak akses untuk
                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ $name }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <x-button.primary wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    <x-slot name="icon">
                        <x-icons.checklist-stepper wire:loading.remove wire:target="save" class="h-5 w-5" />
                        <x-icons.loading wire:loading wire:target="save" class="h-4 w-4 animate-spin" />
                    </x-slot>

                    <span wire:loading.remove wire:target="save">Update User</span>
                    <span wire:loading wire:target="save">Memperbarui...</span>
                </x-button.primary>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-2 lg:grid-cols-3 lg:gap-4">
        <!-- Left Column: Form Details -->
        <div class="space-y-2 lg:col-span-2 lg:space-y-4">
            <!-- Informasi Dasar Section -->
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-blue-500/5 blur-3xl transition-colors group-hover:bg-blue-500/10">
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-blue-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Informasi Dasar</h3>
                </div>

                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:gap-4">
                    <div class="space-y-2">
                        <x-input.basic wire:model="name" id="name" name="name" type="text"
                            placeholder="Nama Lengkap User">
                            Nama Lengkap
                        </x-input.basic>
                        @error('name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <x-input.basic wire:model="email" id="email" name="email" type="email"
                            placeholder="user@indodacin.com">
                            Alamat Email
                        </x-input.basic>
                        @error('email')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Akun Section -->
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-orange-500/5 blur-3xl transition-colors group-hover:bg-orange-500/10">
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-orange-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Status & Akses Login</h3>
                </div>

                <div class="grid gap-2 lg:gap-4">
                    <div class="space-y-2">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status Akun</label>
                        <select wire:model.live="is_active"
                            class="block w-full rounded-xl border border-zinc-200 p-2.5 text-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-zinc-800 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500"
                            x-bind:class="dynamicBg ?
                                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div x-show="$wire.is_active == 0" x-cloak class="space-y-2">
                        <x-input.textarea wire:model="deactivation_reason" id="deactivation_reason"
                            name="deactivation_reason" placeholder="Contoh: Resign atau Penonaktifan Sementara"
                            :labels="true" :textLabel="'Alasan Dinonaktifkan'" />
                        @error('deactivation_reason')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Keamanan Section -->
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-purple-500/5 blur-3xl transition-colors group-hover:bg-purple-500/10">
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-purple-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Ganti Password (Opsional)</h3>
                </div>

                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:gap-4">
                    <div class="space-y-2">
                        <x-input.basic wire:model="password" id="password" name="password" type="password"
                            placeholder="••••••••">
                            Password Baru
                        </x-input.basic>
                        @error('password')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <x-input.basic wire:model="password_confirmation" id="password_confirmation"
                            name="password_confirmation" type="password" placeholder="••••••••">
                            Konfirmasi Password
                        </x-input.basic>
                        @error('password_confirmation')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <p class="mt-4 text-[10px] italic text-gray-500">* Kosongkan password jika tidak ingin mengubahnya.</p>
            </div>
        </div>

        <!-- Right Column: Role Selection -->
        <div class="space-y-2 lg:space-y-4">
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-green-500/5 blur-3xl transition-colors group-hover:bg-green-500/10">
                </div>

                <div class="mb-6 flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-1 rounded-full bg-green-600"></div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Update Role & Hak Akses</h3>
                    </div>

                    {{-- Alpine Search --}}
                    <div class="relative w-full" x-data="{ search: '' }">

                        <x-input.basic x-model="search" :labels="false" id="search" name="search"
                            placeholder="Cari role..." />

                        <div class="mt-4 max-h-100 overflow-y-auto pr-1">
                            <div class="flex flex-col gap-2">
                                @foreach ($list_roles as $role)
                                    <label
                                        x-show="search === '' || '{{ strtolower($role->name) }}'.includes(search.toLowerCase())"
                                        class="role-item group/role flex cursor-pointer items-center gap-3 rounded-2xl border border-zinc-200 p-4 transition-all hover:border-blue-300 hover:bg-blue-50 dark:border-zinc-800 dark:hover:border-blue-700 dark:hover:bg-blue-900/20">
                                        <input wire:model="selected_roles" type="checkbox"
                                            value="{{ $role->name }}"
                                            class="h-5 w-5 rounded-lg border-zinc-200 text-blue-600 focus:ring-blue-500">
                                        <span
                                            class="text-sm font-medium text-gray-700 transition-colors group-hover/role:text-blue-600 dark:text-gray-200">{{ $role->name }}</span>
                                    </label>
                                @endforeach

                                {{-- Empty State --}}
                                <div x-cloak
                                    x-show="search !== '' && ![...$el.parentElement.querySelectorAll('.role-item')].some(el => el.style.display !== 'none')"
                                    class="flex flex-col items-center justify-center py-8 text-gray-400">
                                    <x-icons.info class="mb-2 h-8 w-8 opacity-50" />
                                    <span class="text-xs">Role "<span x-text="search"></span>" tidak ditemukan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('selected_roles')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
