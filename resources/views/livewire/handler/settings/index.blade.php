{{-- Goal: Website Attributes & Settings Management Page, Livewire: Yes, Alpine: activeTab, Livewire: Yes --}}
<div class="space-y-4" x-data="{ activeTab: @entangle('activeTab').live }">
    {{-- Modern Glassmorphic Pill Tab Controller --}}
    <div class="no-scrollbar overflow-x-auto pb-1">
        <nav class="inline-flex min-w-full items-center gap-1.5 rounded-2xl border p-1.5 transition-all duration-200"
            x-bind:class="dynamicBg
                ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <button type="button" @click="activeTab = 'branding'"
                :class="activeTab === 'branding'
                    ?
                    'bg-red-600 text-white shadow-md shadow-red-500/20' :
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100'"
                class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200">
                <x-icons.user-setting class="h-4 w-4 shrink-0" />
                <span>Branding & Judul</span>
            </button>

            <button type="button" @click="activeTab = 'media'"
                :class="activeTab === 'media'
                    ?
                    'bg-red-600 text-white shadow-md shadow-red-500/20' :
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100'"
                class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200">
                <x-icons.camera class="h-4 w-4 shrink-0" />
                <span>Logo & Favicon</span>
            </button>

            <button type="button" @click="activeTab = 'seo'"
                :class="activeTab === 'seo'
                    ?
                    'bg-red-600 text-white shadow-md shadow-red-500/20' :
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100'"
                class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200">
                <x-icons.globe class="h-4 w-4 shrink-0" />
                <span>SEO & Meta Tag</span>
            </button>

            <button type="button" @click="activeTab = 'footer'"
                :class="activeTab === 'footer'
                    ?
                    'bg-red-600 text-white shadow-md shadow-red-500/20' :
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100'"
                class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200">
                <x-icons.window class="h-4 w-4 shrink-0" />
                <span>Footer & Hak Cipta</span>
            </button>

            <button type="button" @click="activeTab = 'contact'"
                :class="activeTab === 'contact'
                    ?
                    'bg-red-600 text-white shadow-md shadow-red-500/20' :
                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100'"
                class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-semibold transition-all duration-200">
                <x-icons.phone class="h-4 w-4 shrink-0" />
                <span>Kontak & Sosmed</span>
            </button>
        </nav>
    </div>

    {{-- Main Content Card Form --}}
    <form wire:submit.prevent="save">
        <div x-bind:class="dynamicBg
            ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
            class="rounded-2xl border p-5 sm:p-6">

            {{-- TAB 1: BRANDING --}}
            <div x-show="activeTab === 'branding'" x-cloak>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-3 dark:border-zinc-800/60">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                            <x-icons.user-setting class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Identitas & Judul Website</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Pengaturan nama brand, judul
                                sidebar, dan teks animasi login</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-input.basic wire:model="site_name" label="Nama Utama Website (App Name)"
                            placeholder="e.g. Indodacin" />
                        <x-input.basic wire:model="site_title" label="Judul Tab Browser (Default Meta Title)"
                            placeholder="e.g. Dashboard System" />
                        <x-input.basic wire:model="sidebar_title" label="Judul pada Sidebar Navigation"
                            placeholder="e.g. Attendance" />
                        <x-input.basic wire:model="auth_subtitle" label="Tagline Animasi pada Halaman Login"
                            placeholder="e.g. Presisi Utama" />
                        <x-input.basic wire:model="app_version" label="Versi Aplikasi / Sistem"
                            placeholder="e.g. v2.4.0" />
                    </div>

                    <x-input.textarea wire:model="auth_description" label="Deskripsi pada Halaman Login / Auth"
                        rows="3"
                        placeholder="Sistem informasi terpadu untuk koordinasi, pelaporan, dan manajemen data operasional secara langsung." />
                </div>
            </div>

            {{-- TAB 2: MEDIA --}}
            <div x-show="activeTab === 'media'" x-cloak>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-3 dark:border-zinc-800/60">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                            <x-icons.camera class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Logo & Favicon Website</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Unggah logo sidebar, icon browser
                                tab, dan Apple touch icon</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        {{-- Logo Website --}}
                        <div x-data="{ logoName: null, logoUploading: false, logoProgress: 0 }"
                            x-on:livewire-upload-start="if ($event.detail.property === 'new_logo') { logoUploading = true; logoProgress = 0; }"
                            x-on:livewire-upload-progress="if (logoUploading) logoProgress = $event.detail.progress"
                            x-on:livewire-upload-finish="if ($event.detail.property === 'new_logo') { logoUploading = false; logoProgress = 100; }"
                            x-on:livewire-upload-error="if ($event.detail.property === 'new_logo') { logoUploading = false; logoProgress = 0; }"
                            x-on:livewire-upload-cancel="if ($event.detail.property === 'new_logo') { logoUploading = false; logoProgress = 0; }"
                            class="flex flex-col gap-3 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/40 dark:hover:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200">Logo Utama / Sidebar
                                    </p>
                                    <p class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">Logo brand di header
                                        sidebar</p>
                                </div>
                                <span
                                    class="rounded-full bg-zinc-200/80 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">PNG
                                    / SVG</span>
                            </div>

                            <div
                                class="relative flex h-32 w-full items-center justify-center overflow-hidden rounded-xl border border-dashed border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-950/80">
                                @if ($new_logo)
                                    <img src="{{ $new_logo->temporaryUrl() }}"
                                        class="max-h-24 max-w-full object-contain p-2" />
                                    <span
                                        class="absolute right-2 top-2 rounded-full bg-emerald-500 p-1 text-white shadow-sm">
                                        <x-icons.check class="h-3 w-3" />
                                    </span>
                                @elseif ($logo_path)
                                    <img src="{{ asset('storage/' . $logo_path) }}"
                                        class="max-h-24 max-w-full object-contain p-2" />
                                @else
                                    <img src="{{ asset('images/brand/logo.png') }}"
                                        class="max-h-24 max-w-full object-contain p-2 opacity-70" />
                                @endif

                                {{-- Upload progress overlay --}}
                                <div x-show="logoUploading" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-2.5 rounded-xl bg-white/95 p-4 backdrop-blur-md dark:bg-zinc-950/95">
                                    <div class="flex items-center gap-1.5">
                                        <x-icons.clockwise class="h-4 w-4 animate-spin text-red-600 dark:text-red-500" />
                                        <span class="text-xs font-bold text-zinc-900 dark:text-white"
                                            x-text="logoProgress + '%'"></span>
                                    </div>
                                    <div class="h-2 w-4/5 overflow-hidden rounded-full bg-zinc-200/80 p-0.5 dark:bg-zinc-800">
                                        <div class="h-full rounded-full bg-linear-to-r from-red-500 to-rose-600 transition-all duration-300 ease-out shadow-sm"
                                            :style="'width:' + logoProgress + '%'"></div>
                                    </div>
                                    <p class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">Mengunggah file...</p>
                                </div>
                            </div>

                            {{-- Custom Upload Button --}}
                            <div>
                                <label for="upload-logo" :class="logoUploading ? 'pointer-events-none opacity-60' : ''"
                                    class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-zinc-300 bg-white px-3 py-2.5 text-xs font-medium text-zinc-600 transition-all duration-200 hover:border-red-400 hover:bg-red-50 hover:text-red-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-red-500/60 dark:hover:bg-red-950/20 dark:hover:text-red-400">
                                    <x-icons.cloud-upload x-show="!logoUploading" class="h-4 w-4 shrink-0" />
                                    <x-icons.clockwise x-show="logoUploading" x-cloak
                                        class="h-4 w-4 shrink-0 animate-spin" />
                                    <span
                                        x-text="logoUploading ? (logoProgress + '% mengunggah...') : (logoName ?? 'Pilih File Logo')"></span>
                                </label>
                                <input id="upload-logo" type="file" wire:model="new_logo" accept=".png,.jpg,.jpeg,.svg,.webp,image/png,image/jpeg,image/svg+xml,image/webp"
                                    class="sr-only" @change="logoName = $event.target.files[0]?.name ?? null" />
                                <x-input.error :messages="$errors->get('new_logo')" />
                            </div>
                        </div>

                        {{-- Favicon --}}
                        <div x-data="{ faviconName: null, faviconUploading: false, faviconProgress: 0 }"
                            x-on:livewire-upload-start="if ($event.detail.property === 'new_favicon') { faviconUploading = true; faviconProgress = 0; }"
                            x-on:livewire-upload-progress="if (faviconUploading) faviconProgress = $event.detail.progress"
                            x-on:livewire-upload-finish="if ($event.detail.property === 'new_favicon') { faviconUploading = false; faviconProgress = 100; }"
                            x-on:livewire-upload-error="if ($event.detail.property === 'new_favicon') { faviconUploading = false; faviconProgress = 0; }"
                            x-on:livewire-upload-cancel="if ($event.detail.property === 'new_favicon') { faviconUploading = false; faviconProgress = 0; }"
                            class="flex flex-col gap-3 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/40 dark:hover:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200">Favicon Tab Browser
                                    </p>
                                    <p class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">Icon kecil di tab
                                        browser</p>
                                </div>
                                <span
                                    class="rounded-full bg-zinc-200/80 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">ICO
                                    / PNG</span>
                            </div>

                            <div
                                class="relative flex h-32 w-full items-center justify-center overflow-hidden rounded-xl border border-dashed border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-950/80">
                                @if ($new_favicon)
                                    <img src="{{ $new_favicon->temporaryUrl() }}" class="h-16 w-16 object-contain" />
                                    <span
                                        class="absolute right-2 top-2 rounded-full bg-emerald-500 p-1 text-white shadow-sm">
                                        <x-icons.check class="h-3 w-3" />
                                    </span>
                                @elseif ($favicon_path)
                                    <img src="{{ asset('storage/' . $favicon_path) }}"
                                        class="h-16 w-16 object-contain" />
                                @else
                                    <img src="{{ asset('images/brand/logo.ico') }}"
                                        class="h-16 w-16 object-contain opacity-70" />
                                @endif

                                {{-- Upload progress overlay --}}
                                <div x-show="faviconUploading" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-2.5 rounded-xl bg-white/95 p-4 backdrop-blur-md dark:bg-zinc-950/95">
                                    <div class="flex items-center gap-1.5">
                                        <x-icons.clockwise class="h-4 w-4 animate-spin text-red-600 dark:text-red-500" />
                                        <span class="text-xs font-bold text-zinc-900 dark:text-white"
                                            x-text="faviconProgress + '%'"></span>
                                    </div>
                                    <div class="h-2 w-4/5 overflow-hidden rounded-full bg-zinc-200/80 p-0.5 dark:bg-zinc-800">
                                        <div class="h-full rounded-full bg-linear-to-r from-red-500 to-rose-600 transition-all duration-300 ease-out shadow-sm"
                                            :style="'width:' + faviconProgress + '%'"></div>
                                    </div>
                                    <p class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">Mengunggah file...</p>
                                </div>
                            </div>

                            {{-- Custom Upload Button --}}
                            <div>
                                <label for="upload-favicon"
                                    :class="faviconUploading ? 'pointer-events-none opacity-60' : ''"
                                    class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-zinc-300 bg-white px-3 py-2.5 text-xs font-medium text-zinc-600 transition-all duration-200 hover:border-red-400 hover:bg-red-50 hover:text-red-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-red-500/60 dark:hover:bg-red-950/20 dark:hover:text-red-400">
                                    <x-icons.cloud-upload x-show="!faviconUploading" class="h-4 w-4 shrink-0" />
                                    <x-icons.clockwise x-show="faviconUploading" x-cloak
                                        class="h-4 w-4 shrink-0 animate-spin" />
                                    <span
                                        x-text="faviconUploading ? (faviconProgress + '% mengunggah...') : (faviconName ?? 'Pilih File Favicon')"></span>
                                </label>
                                <input id="upload-favicon" type="file" wire:model="new_favicon"
                                    accept=".ico,.png,.jpg,.jpeg,.svg,image/x-icon,image/png,image/jpeg,image/svg+xml" class="sr-only"
                                    @change="faviconName = $event.target.files[0]?.name ?? null" />
                                <x-input.error :messages="$errors->get('new_favicon')" />
                            </div>
                        </div>

                        {{-- Apple Touch Icon --}}
                        <div x-data="{ touchIconName: null, touchUploading: false, touchProgress: 0 }"
                            x-on:livewire-upload-start="if ($event.detail.property === 'new_apple_touch_icon') { touchUploading = true; touchProgress = 0; }"
                            x-on:livewire-upload-progress="if (touchUploading) touchProgress = $event.detail.progress"
                            x-on:livewire-upload-finish="if ($event.detail.property === 'new_apple_touch_icon') { touchUploading = false; touchProgress = 100; }"
                            x-on:livewire-upload-error="if ($event.detail.property === 'new_apple_touch_icon') { touchUploading = false; touchProgress = 0; }"
                            x-on:livewire-upload-cancel="if ($event.detail.property === 'new_apple_touch_icon') { touchUploading = false; touchProgress = 0; }"
                            class="flex flex-col gap-3 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/40 dark:hover:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200">Apple Touch Icon</p>
                                    <p class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">Icon aplikasi iOS /
                                        Web App</p>
                                </div>
                                <span
                                    class="rounded-full bg-zinc-200/80 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">PNG</span>
                            </div>

                            <div
                                class="relative flex h-32 w-full items-center justify-center overflow-hidden rounded-xl border border-dashed border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-950/80">
                                @if ($new_apple_touch_icon)
                                    <img src="{{ $new_apple_touch_icon->temporaryUrl() }}"
                                        class="h-20 w-20 rounded-xl object-contain" />
                                    <span
                                        class="absolute right-2 top-2 rounded-full bg-emerald-500 p-1 text-white shadow-sm">
                                        <x-icons.check class="h-3 w-3" />
                                    </span>
                                @elseif ($apple_touch_icon_path)
                                    <img src="{{ asset('storage/' . $apple_touch_icon_path) }}"
                                        class="h-20 w-20 rounded-xl object-contain" />
                                @else
                                    <img src="{{ asset('images/brand/apple-touch-icon.png') }}"
                                        class="h-20 w-20 rounded-xl object-contain opacity-70" />
                                @endif

                                {{-- Upload progress overlay --}}
                                <div x-show="touchUploading" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-2.5 rounded-xl bg-white/95 p-4 backdrop-blur-md dark:bg-zinc-950/95">
                                    <div class="flex items-center gap-1.5">
                                        <x-icons.clockwise class="h-4 w-4 animate-spin text-red-600 dark:text-red-500" />
                                        <span class="text-xs font-bold text-zinc-900 dark:text-white"
                                            x-text="touchProgress + '%'"></span>
                                    </div>
                                    <div class="h-2 w-4/5 overflow-hidden rounded-full bg-zinc-200/80 p-0.5 dark:bg-zinc-800">
                                        <div class="h-full rounded-full bg-linear-to-r from-red-500 to-rose-600 transition-all duration-300 ease-out shadow-sm"
                                            :style="'width:' + touchProgress + '%'"></div>
                                    </div>
                                    <p class="text-[11px] font-medium text-zinc-500 dark:text-zinc-400">Mengunggah file...</p>
                                </div>
                            </div>

                            {{-- Custom Upload Button --}}
                            <div>
                                <label for="upload-apple-touch"
                                    :class="touchUploading ? 'pointer-events-none opacity-60' : ''"
                                    class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-zinc-300 bg-white px-3 py-2.5 text-xs font-medium text-zinc-600 transition-all duration-200 hover:border-red-400 hover:bg-red-50 hover:text-red-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-red-500/60 dark:hover:bg-red-950/20 dark:hover:text-red-400">
                                    <x-icons.cloud-upload x-show="!touchUploading" class="h-4 w-4 shrink-0" />
                                    <x-icons.clockwise x-show="touchUploading" x-cloak
                                        class="h-4 w-4 shrink-0 animate-spin" />
                                    <span
                                        x-text="touchUploading ? (touchProgress + '% mengunggah...') : (touchIconName ?? 'Pilih File Icon')"></span>
                                </label>
                                <input id="upload-apple-touch" type="file" wire:model="new_apple_touch_icon"
                                    accept=".png,.jpg,.jpeg,.svg,image/png,image/jpeg,image/svg+xml" class="sr-only"
                                    @change="touchIconName = $event.target.files[0]?.name ?? null" />
                                <x-input.error :messages="$errors->get('new_apple_touch_icon')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: SEO --}}
            <div x-show="activeTab === 'seo'" x-cloak>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-3 dark:border-zinc-800/60">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                            <x-icons.globe class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">SEO & Meta Tags</h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Optimasi kata kunci pencarian dan
                                pengenal mesin pencari</p>
                        </div>
                    </div>

                    <x-input.textarea wire:model="meta_description" label="Meta Description" rows="3"
                        placeholder="Deskripsi ringkas website untuk pencarian mesin pencari..." />

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-input.basic wire:model="meta_keywords" label="Meta Keywords (Pisahkan dengan koma)"
                            placeholder="dashboard, system, indodacin" />
                        <x-input.basic wire:model="meta_author" label="Meta Author / Pemilik Hak Cipta"
                            placeholder="PT. Indodacin Presisi Utama" />
                        <x-input.basic wire:model="google_analytics_id"
                            label="Google Analytics Tracking ID (Opsional)" placeholder="e.g. G-XXXXXXXXXX" />
                    </div>
                </div>
            </div>

            {{-- TAB 4: FOOTER --}}
            <div x-show="activeTab === 'footer'" x-cloak>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-3 dark:border-zinc-800/60">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                            <x-icons.window class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Informasi Footer & Hak Cipta
                            </h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Teks hak cipta dan tautan lisensi
                                di bagian bawah dashboard</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-input.basic wire:model="footer_company" label="Nama Perusahaan di Footer"
                            placeholder="PT. Indodacin Presisi Utama™" />
                        <x-input.basic wire:model="footer_url" label="Tautan URL Perusahaan"
                            placeholder="https://indodacin.com" />
                        <x-input.basic wire:model="footer_copyright" label="Teks Hak Cipta (Copyright)"
                            placeholder="All Rights Reserved." />
                    </div>
                </div>
            </div>

            {{-- TAB 5: KONTAK & SOSMED --}}
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-3 dark:border-zinc-800/60">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                            <x-icons.phone class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Kontak Support & Sosial Media
                            </h3>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Nomor bantuan customer service dan
                                tautan media sosial</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-input.basic wire:model="contact_email" label="Email Support / CS"
                            placeholder="support@indodacin.com" />
                        <x-input.basic wire:model="whatsapp_number"
                            label="Nomor WhatsApp CS (Format Internasional tanpa +)" placeholder="628123456789" />
                        <x-input.basic wire:model="social_facebook" label="Link Facebook"
                            placeholder="https://facebook.com/..." />
                        <x-input.basic wire:model="social_instagram" label="Link Instagram"
                            placeholder="https://instagram.com/..." />
                        <x-input.basic wire:model="social_linkedin" label="Link LinkedIn"
                            placeholder="https://linkedin.com/in/..." />
                    </div>

                    <x-input.textarea wire:model="office_address" label="Alamat Kantor" rows="2"
                        placeholder="Jl. Raya Industri No. 88, Jakarta" />
                </div>
            </div>

            {{-- Save Button Footer --}}
            <div class="mt-6 flex items-center justify-end border-t border-zinc-200/80 pt-4 dark:border-zinc-800/80">
                <x-button.primary type="submit" wire:loading.attr="disabled"
                    wire:target="save, new_logo, new_favicon, new_apple_touch_icon">
                    <x-slot name="icon">
                        <x-icons.check-circle class="h-4 w-4" />
                    </x-slot>
                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </x-button.primary>
            </div>
        </div>
    </form>
</div>
