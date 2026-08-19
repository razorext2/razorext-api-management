<div class="flex flex-col gap-4">

    {{-- Grid Layout for Profile Sections --}}
    <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3">

        {{-- Left Info Card (Spans 2 columns on lg) --}}
        <div class="flex flex-col self-start rounded-xl border p-5 shadow-sm transition-all duration-300 lg:col-span-2"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800'">

            <div class="flex flex-col gap-4">
                {{-- Card Header --}}
                <div class="flex items-center gap-2 border-b border-zinc-200 pb-4 dark:border-zinc-800">
                    <div class="h-2 w-2 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)] dark:bg-red-700">
                    </div>
                    <h3 class="text-base font-bold tracking-wider text-zinc-800 md:text-xl dark:text-white">
                        Informasi Akun
                    </h3>
                </div>

                {{-- User Header --}}
                <div class="flex flex-row items-center gap-x-5">
                    <div class="group relative">
                        <div
                            class="bg-linear-to-br absolute -inset-0.5 rounded-[0.85rem] from-red-500 to-red-700 opacity-60 blur-sm transition-opacity duration-500 group-hover:opacity-100 dark:from-red-600/50 dark:to-red-900/50">
                        </div>
                        <img class="dark:border-dark-secondary relative h-20 w-20 rounded-xl border-2 border-white object-cover shadow-sm lg:h-24 lg:w-24"
                            src="{{ auth()->user()->profile_pic ? asset('storage/profile-pictures/' . auth()->user()->profile_pic) : asset('images/defaults/profile-picture-5.jpg') }}"
                            alt="user photo" loading="lazy"
                            onerror="this.src = '{{ asset('images/defaults/noImage.webp') }}'">
                    </div>

                    <div class="flex flex-col gap-y-1">
                        <span
                            class="w-fit rounded bg-red-50 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-widest text-red-600 dark:bg-red-500/10 dark:text-red-400">
                            {{ auth()->user()->roles->first()->name ?? 'User' }}
                        </span>
                        <h4 class="text-xl font-black text-zinc-900 lg:text-2xl dark:text-white">
                            {{ auth()->user()->name }}</h4>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                            {{ auth()->user()->bio ?? 'Biografi belum disetel' }}</p>
                    </div>
                </div>

                {{-- Data Lists --}}
                <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="flex flex-col gap-4">
                        {{-- Email --}}
                        <div
                            class="dark:bg-dark-secondary/30 dark:hover:bg-dark-secondary/50 flex items-center gap-3 rounded-lg border border-zinc-100 bg-zinc-50/50 p-3 transition-all hover:bg-zinc-50 dark:border-zinc-800/50">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400">
                                <x-icons.envelope class="h-5 w-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    Alamat Email</p>
                                <p class="truncate text-sm font-semibold text-zinc-800 dark:text-zinc-200"
                                    title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        {{-- Status Akun --}}
                        <div
                            class="dark:bg-dark-secondary/30 dark:hover:bg-dark-secondary/50 flex items-center gap-3 rounded-lg border border-zinc-100 bg-zinc-50/50 p-3 transition-all hover:bg-zinc-50 dark:border-zinc-800/50">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400">
                                <x-icons.badge-check class="h-5 w-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    Status Akun</p>
                                <div class="mt-0.5">
                                    <span
                                        class="{{ auth()->user()->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }} inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold">
                                        {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        {{-- Terdaftar Sejak --}}
                        <div
                            class="dark:bg-dark-secondary/30 dark:hover:bg-dark-secondary/50 flex items-center gap-3 rounded-lg border border-zinc-100 bg-zinc-50/50 p-3 transition-all hover:bg-zinc-50 dark:border-zinc-800/50">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400">
                                <x-icons.calendar class="h-5 w-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    Terdaftar Sejak</p>
                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                                    {{ auth()->user()->created_at ? auth()->user()->created_at->translatedFormat('d F Y') : '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Verifikasi Email --}}
                        <div
                            class="dark:bg-dark-secondary/30 dark:hover:bg-dark-secondary/50 flex items-center gap-3 rounded-lg border border-zinc-100 bg-zinc-50/50 p-3 transition-all hover:bg-zinc-50 dark:border-zinc-800/50">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400">
                                <x-icons.clock class="h-5 w-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    Verifikasi Email</p>
                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">
                                    {{ auth()->user()->email_verified_at ? auth()->user()->email_verified_at->translatedFormat('d F Y') : 'Belum Diverifikasi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Info Card (Spans 1 column on lg) --}}
        <div class="flex flex-col gap-4 self-start rounded-xl border p-5 shadow-sm transition-all duration-300 lg:col-span-1"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800'">

            <div class="flex items-center gap-2 border-b border-zinc-200/60 pb-4 dark:border-zinc-800/60">
                <x-icons.badge-check class="h-5 w-5 text-red-500 dark:text-red-400" />
                <p class="text-sm font-bold text-zinc-800 dark:text-zinc-200">Hak Akses (Permissions)</p>
            </div>

            <div class="custom-scrollbar flex max-h-80 flex-row flex-wrap content-start gap-1.5 overflow-y-auto pr-1">
                @forelse (auth()->user()->getPermissionsViaRoles() as $permission)
                    <span
                        class="dark:bg-dark-primary w-fit rounded-md border border-zinc-200/80 bg-white px-2.5 py-1 text-xs font-semibold text-zinc-600 shadow-sm transition-all duration-200 hover:scale-[1.02] hover:border-red-400 hover:bg-red-50/30 hover:text-red-600 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-red-500/50 dark:hover:bg-red-950/20 dark:hover:text-red-400">
                        {{ $permission->name }}
                    </span>
                @empty
                    <div class="flex w-full flex-col items-center justify-center py-12 text-center">
                        <x-icons.info-circle class="mb-2 h-8 w-8 text-zinc-300 dark:text-zinc-600" />
                        <p class="text-xs italic text-zinc-400 dark:text-zinc-500">Tidak ada permission.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Call To Action Widget --}}
    <div
        class="dark:from-dark-secondary dark:to-dark-primary bg-linear-to-br group relative overflow-hidden rounded-xl from-red-600 to-red-800 px-6 py-6 shadow-md shadow-red-200 ring-1 ring-red-500/50 dark:shadow-none dark:ring-zinc-800">
        {{-- Decorative background glow --}}
        <div
            class="absolute -right-12 -top-12 h-44 w-44 rounded-full bg-white/10 blur-3xl transition-transform duration-1000 group-hover:scale-150 dark:bg-red-500/10">
        </div>

        <div class="relative z-10 flex flex-col items-center gap-y-4">
            <div
                class="dark:bg-dark-secondary/50 rounded-full border border-white/20 bg-white/10 p-4 backdrop-blur-sm dark:border-zinc-800">
                <x-icons.file-pen class="h-10 w-10 text-white drop-shadow-sm dark:text-red-500" />
            </div>

            <div class="text-center">
                <h3 class="text-xl font-bold tracking-wide text-white drop-shadow-sm lg:text-2xl">Mulai buat
                    laporanmu!</h3>
                <p class="mx-auto mt-2 max-w-sm text-sm text-red-100 dark:text-zinc-400">
                    Pelaporan yang konsisten mempermudah koordinasi dan tata kelola data sistem secara menyeluruh.
                </p>
            </div>

            <div class="mt-2 flex w-full flex-wrap justify-center gap-3">
                @can('collect-create')
                    @if (Route::has('collect.index'))
                        <x-button.link href="{{ route('collect.index') }}"
                            class="dark:bg-dark-primary! min-w-35 bg-white! text-red-700! hover:bg-red-50! dark:border-zinc-700! dark:text-white! dark:hover:border-red-500! w-full justify-center border-none font-bold drop-shadow-sm sm:w-auto">
                            <span>Kolektor</span>
                            <x-icons.arrow-right
                                class="ml-1 h-4 w-4 -rotate-45 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                        </x-button.link>
                    @endif
                @endcan
                @can('driver-create')
                    @if (Route::has('driver.create'))
                        <x-button.link href="{{ route('driver.create') }}"
                            class="dark:bg-dark-primary! min-w-35 bg-white! text-red-700! hover:bg-red-50! dark:border-zinc-700! dark:text-white! dark:hover:border-red-500! w-full justify-center border-none font-bold drop-shadow-sm sm:w-auto">
                            <span>Driver</span>
                            <x-icons.arrow-right
                                class="ml-1 h-4 w-4 -rotate-45 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                        </x-button.link>
                    @endif
                @endcan
                @can('sales-create')
                    @if (Route::has('sales.create'))
                        <x-button.link href="{{ route('sales.create') }}"
                            class="dark:bg-dark-primary! min-w-35 bg-white! text-red-700! hover:bg-red-50! dark:border-zinc-700! dark:text-white! dark:hover:border-red-500! w-full justify-center border-none font-bold drop-shadow-sm sm:w-auto">
                            <span>Sales</span>
                            <x-icons.arrow-right
                                class="ml-1 h-4 w-4 -rotate-45 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                        </x-button.link>
                    @endif
                @endcan
                @can('technician-create')
                    @if (Route::has('technician.index'))
                        <x-button.link href="{{ route('technician.index') }}"
                            class="dark:bg-dark-primary! min-w-35 bg-white! text-red-700! hover:bg-red-50! dark:border-zinc-700! dark:text-white! dark:hover:border-red-500! w-full justify-center border-none font-bold drop-shadow-sm sm:w-auto">
                            <span>Teknisi</span>
                            <x-icons.arrow-right
                                class="ml-1 h-4 w-4 -rotate-45 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                        </x-button.link>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</div>
