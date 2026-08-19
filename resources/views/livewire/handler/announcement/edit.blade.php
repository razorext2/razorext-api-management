{{-- Goal: Edit form page for announcement, Livewire: Handler\Announcement\Edit, Alpine: Quill editor, user search --}}
<div class="w-full space-y-6">
    <!-- Top Header Navigation -->
    <div class="rounded-xl border border-zinc-200 p-6 shadow-2xl transition-all duration-500 ease-in-out dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div class="flex items-center">
                <x-button.danger class="my-auto me-4 max-h-10" href="{{ route('announcement.index') }}" wire:navigate>
                    <x-icons.angle-left class="h-5 w-5" />
                </x-button.danger>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">Edit Pengumuman</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui informasi pengumuman yang sudah ada.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <x-button.primary wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    <x-slot name="icon">
                        <x-icons.angle-right wire:loading.remove wire:target="save" class="icon h-5 w-5" />
                        <x-icons.loading wire:loading wire:target="save" class="h-4 w-4 animate-spin" />
                    </x-slot>

                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Memproses...</span>
                </x-button.primary>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-2 lg:grid-cols-3 lg:gap-4">
        <!-- Left Column: Form Details -->
        <div class="space-y-2 lg:col-span-2 lg:space-y-4">
            <!-- Informasi Pengumuman -->
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-blue-500/5 blur-3xl transition-colors group-hover:bg-blue-500/10">
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-blue-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Informasi Pengumuman</h3>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    {{-- Title --}}
                    <div class="space-y-2">
                        <x-input.basic wire:model="title" id="title" name="title" type="text"
                            placeholder="Judul pengumuman">
                            Judul
                        </x-input.basic>
                        @error('title')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="space-y-2">
                        <x-input.textarea wire:model="description" id="description" name="description"
                            textLabel="Deskripsi" placeholder="Tulis isi pengumuman di sini..." rows="8" />
                        @error('description')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Target Pengumuman -->
            <div class="group relative rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-xl">
                    <div
                        class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-purple-500/5 blur-3xl transition-colors group-hover:bg-purple-500/10">
                    </div>
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-purple-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Target Pengumuman</h3>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    {{-- Target Type --}}
                    <div class="space-y-2">
                        <x-input.select id="target_type" name="target_type" wire:model.live="target_type"
                            :defaultOption="null" :options="[
                                'all' => 'Semua User',
                                'role' => 'Berdasarkan Role',
                                'user' => 'Pilih User Spesifik',
                            ]" :labels="true" :textLabel="'Tujuan Pengumuman'" />
                        @error('target_type')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Target Roles --}}
                    @if ($target_type === 'role')
                        <div class="space-y-4" x-data="{ search: '' }">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Role
                                    Target</label>
                                <div class="relative w-full md:max-w-xs">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <x-icons.search class="h-4 w-4 text-gray-400" />
                                    </div>
                                    <input x-model="search" type="text"
                                        class="block w-full rounded-xl border-zinc-200 pl-10 text-xs focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-800 dark:bg-dark-secondary dark:text-white"
                                        x-bind:class="dynamicBg ?
                                            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                                            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
                                        placeholder="Cari role...">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                                @foreach ($roles as $role)
                                    <label
                                        x-show="search === '' || '{{ strtolower($role->name) }}'.includes(search.toLowerCase())"
                                        class="role-item group/role flex cursor-pointer items-center gap-3 rounded-2xl border border-zinc-200 p-4 transition-all hover:border-blue-300 hover:bg-blue-50 dark:border-zinc-800 dark:hover:border-blue-700 dark:hover:bg-blue-900/20">
                                        <input wire:model="target_roles" type="checkbox" value="{{ $role->id }}"
                                            class="h-5 w-5 rounded-lg border-zinc-200 text-blue-600 focus:ring-blue-500">
                                        <span
                                            class="text-sm font-medium text-gray-700 transition-colors group-hover/role:text-blue-600 dark:text-gray-200">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('target_roles')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- Target Users --}}
                    @if ($target_type === 'user')
                        <div class="w-full" x-data="{
                            open: false,
                            search: '',
                            selectedIds: $wire.entangle('target_users'),
                            users: {{ Js::from($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])) }},
                            get filteredUsers() {
                                if (this.search === '') return this.users.filter(u => !this.selectedIds.includes(u.id)).slice(0, 5);
                                return this.users.filter(u =>
                                    (u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                        (u.kode && u.kode.toLowerCase().includes(this.search.toLowerCase()))) &&
                                    !this.selectedIds.includes(u.id)
                                ).slice(0, 5);
                            },
                            get selectedUsers() {
                                return this.selectedIds.map(id => this.users.find(u => u.id === id)).filter(Boolean);
                            },
                            add(id) {
                                if (!this.selectedIds.includes(id)) {
                                    this.selectedIds.push(id);
                                }
                                this.search = '';
                                this.open = false;
                            },
                            remove(id) {
                                this.selectedIds = this.selectedIds.filter(i => i !== id);
                            }
                        }" @click.away="open = false">
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Pilih User Spesifik
                            </label>

                            {{-- Selected Chips --}}
                            <div class="mb-2 flex flex-wrap gap-2" x-show="selectedUsers.length > 0"
                                style="display: none;">
                                <template x-for="user in selectedUsers" :key="user.id">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        <span x-text="user.name"></span>
                                        <button type="button" @click="remove(user.id)"
                                            class="inline-flex h-4 w-4 shrink-0 items-center justify-center rounded-full text-blue-600 hover:bg-blue-200 hover:text-blue-900 dark:hover:bg-blue-800 dark:hover:text-blue-200">
                                            <x-icons.close class="h-2 w-2" />
                                        </button>
                                    </span>
                                </template>
                            </div>

                            {{-- Search Input --}}
                            <div class="relative">
                                <input type="text" x-model="search" @focus="open = true"
                                    class="{{ $errors->has('target_users') ? 'border-red-500 bg-red-50' : 'border-zinc-200 bg-white' }} block w-full rounded-lg border p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-800 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                    placeholder="Cari nama atau NIP User...">

                                {{-- Dropdown --}}
                                <div x-show="open && filteredUsers.length > 0" x-transition style="display: none;"
                                    class="absolute bottom-full z-10 mb-1 max-h-48 w-full overflow-y-auto rounded-lg bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800 dark:ring-white dark:ring-opacity-10">
                                    <template x-for="user in filteredUsers" :key="user.id">
                                        <button type="button" @click="add(user.id)"
                                            class="flex w-full flex-col items-start px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="user.name"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="user.kode"></span>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="open && search !== '' && filteredUsers.length === 0"
                                    style="display: none;"
                                    class="absolute bottom-full z-10 mb-1 w-full rounded-lg bg-white px-4 py-3 text-sm text-gray-500 shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800 dark:text-gray-400 dark:ring-white dark:ring-opacity-10">
                                    Tidak ada data ditemukan.
                                </div>
                            </div>

                            @error('target_users')
                                <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Lampiran -->
        <div class="space-y-2 lg:space-y-4">
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                <div
                    class="absolute right-0 top-0 -mr-16 -mt-16 h-32 w-32 rounded-full bg-orange-500/5 blur-3xl transition-colors group-hover:bg-orange-500/10">
                </div>

                <div class="mb-8 flex items-center gap-3">
                    <div class="h-10 w-1 rounded-full bg-orange-600"></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Lampiran</h3>
                </div>

                <div class="space-y-4">
                    {{-- File Attachment --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">File PDF (Opsional,
                            Max 2MB)</label>
                        <div
                            class="group relative flex h-40 w-full flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-zinc-200 transition-colors hover:border-blue-500 dark:border-zinc-800">
                            @if ($file)
                                <div class="flex flex-col items-center gap-2">
                                    <x-icons.file-invoice class="h-12 w-12 text-blue-500" />
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $file->getClientOriginalName() }}</span>
                                    <x-button.danger class="px-4! py-2! text-xs!" wire:click="$set('file', null)">
                                        Ganti File
                                    </x-button.danger>
                                </div>
                            @else
                                <div class="flex flex-col items-center gap-3">
                                    <div class="rounded-2xl bg-gray-100 p-4 dark:bg-dark-secondary">
                                        <x-icons.cloud-upload class="h-8 w-8 text-gray-400" />
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pilih atau Seret
                                        File PDF</span>
                                </div>
                                <input wire:model="file" type="file" accept=".pdf"
                                    class="absolute inset-0 cursor-pointer opacity-0">
                            @endif

                            <div wire:loading wire:target="file"
                                class="absolute inset-0 flex items-center justify-center"
                                x-bind:class="dynamicBg ?
                                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                                <div class="h-8 w-8 animate-spin rounded-full border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                        @if ($existing_file)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                File tersimpan: <a href="{{ Storage::url($existing_file) }}" target="_blank"
                                    class="text-blue-600 underline hover:text-blue-700">Lihat PDF</a>
                            </p>
                        @endif
                        @error('file')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div
                    class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-4 dark:border-blue-800/30 dark:bg-blue-900/10">
                    <p class="text-center text-xs font-medium leading-relaxed text-blue-700 dark:text-blue-400">
                        Lampiran yang diunggah akan dapat diunduh oleh user saat membaca pengumuman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
