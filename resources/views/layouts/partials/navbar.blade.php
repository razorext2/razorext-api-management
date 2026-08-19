{{-- Goal: Floating dashboard navbar layout with adjustable background, shadow, and mobile responsiveness, Livewire: Yes, Alpine: Yes --}}
<nav x-data="{
    navVisible: true,
    lastScrollY: 0,
    isMobile() { return window.innerWidth < 768; },
    handleScroll() {
        if (!this.isMobile()) {
            this.navVisible = true;
            return;
        }
        const currentScrollY = window.scrollY;
        if (currentScrollY <= 0) {
            this.navVisible = true;
        } else if (currentScrollY < this.lastScrollY) {
            this.navVisible = true;
        } else if (currentScrollY > this.lastScrollY && currentScrollY > 60) {
            this.navVisible = false;
        }
        this.lastScrollY = currentScrollY;
    }
}" x-init="window.addEventListener('scroll', () => handleScroll(), { passive: true });
window.addEventListener('resize', () => { if (!isMobile()) navVisible = true; }, { passive: true });"
    :class="[
        openSidebar ? 'md:ml-72' : '',
        dynamicBg ?
        'bg-glass-light border-glass-border-light backdrop-blur-md shadow-sm dark:bg-glass-dark dark:border-glass-border-dark dark:shadow-none' :
        'bg-white border-zinc-200 shadow-sm dark:bg-dark-primary dark:border-zinc-800 dark:shadow-none'
    ]"
    :style="{ transform: !navVisible ? 'translateY(calc(-100% - 1rem))' : 'translateY(0)' }"
    style="transition: margin-left 300ms ease-out, transform 300ms ease-in-out;"
    class="fixed left-4 right-4 top-4 z-40 rounded-2xl border px-4 py-2.5 lg:px-6" x-cloak>
    <div class="flex items-center justify-between gap-2">

        {{-- Logo & Toggle --}}
        <div class="flex min-w-0 items-center gap-2">
            {{-- Sidebar Toggle Button (Desktop Only) --}}
            <button @click="openSidebar = !openSidebar" x-data="{ tapping: false }" x-on:mousedown="tapping = true"
                x-on:touchstart="tapping = true" x-on:animationend="tapping = false"
                x-on:animationcancel="tapping = false" :class="{ 'is-tapping': tapping }"
                class="liquid-btn hidden rounded-xl p-2 text-zinc-500 transition-colors hover:bg-zinc-100/50 md:flex dark:text-zinc-400 dark:hover:bg-zinc-800/50">
                <span x-show="!openSidebar">
                    <x-icons.bar class="h-5 w-5" />
                </span>
                <span x-show="openSidebar">
                    <x-icons.close class="h-5 w-5" />
                </span>
            </button>

            <div :class="openSidebar ? 'md:opacity-0 md:pointer-events-none md:max-w-0 md:-translate-x-5' :
                'opacity-100 md:max-w-36 translate-x-0'"
                class="flex shrink-0 transform items-center justify-start overflow-hidden transition-[opacity,transform,max-width] duration-300 ease-in-out">
                <a class="flex items-center gap-2" href="{{ config('app.url') }}">
                    <img class="h-7 w-7 rounded-lg object-contain sm:h-8 sm:w-8"
                        src="{{ asset('images/brand/logo.png') }}" alt="Attendance Logo" loading="lazy" />
                    <span
                        class="hidden text-sm font-bold italic tracking-wide text-zinc-900 sm:block sm:text-base dark:text-white">Attendance</span>
                </a>
            </div>

            {{-- Breadcrumb Placeholder (Sticky) --}}
            <div id="navbar-breadcrumb-container"
                class="flex h-6 min-w-0 items-center border-l border-zinc-200/50 pl-3 sm:pl-4 dark:border-zinc-800/50">
            </div>
        </div>

        {{-- Points (Teknisi) --}}
        <div id="point-container" class="hidden sm:block">
            @if (auth()->user()->hasRole('Teknisi'))
                <livewire:widget.technician.points-accumulation />
            @endif
        </div>

        {{-- Right Actions --}}
        <div class="flex items-center justify-end gap-2 sm:gap-3">

            <livewire:utils.ping-checker />

            {{-- Notification --}}
            <div id="notifications" class="relative" x-data="{
                open: false,
                dropdownStyle: '',
                updatePosition() {
                    const btn = document.getElementById('notificationButton');
                    if (!btn) return;
                    const rect = btn.getBoundingClientRect();
                    const top = rect.bottom + 20;
                    if (window.innerWidth < 640) {
                        // Mobile: full-width with horizontal padding
                        this.dropdownStyle = `position:fixed;top:${top}px;left:12px;right:12px;width:auto;`;
                    } else {
                        // Desktop: right-aligned fixed width
                        const right = window.innerWidth - rect.right;
                        this.dropdownStyle = `position:fixed;top:${top}px;right:${right}px;width:384px;`;
                    }
                }
            }" @click.outside="open = false">
                <button
                    @click="open = !open; if(open) { Livewire.dispatch('notification-received'); updatePosition(); }"
                    class="relative rounded-xl p-2 text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-300 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white dark:focus:ring-zinc-700"
                    id="notificationButton" type="button" wire:ignore>
                    <span class="sr-only">View notifications</span>
                    <livewire:notification-bell />
                </button>

                {{-- Notification Dropdown (teleported to escape nav backdrop-filter context) --}}
                <template x-teleport="body">
                    <div x-show="open" @click.outside="open = false" style="display: none;"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        :style="dropdownStyle"
                        x-bind:class="dynamicBg
                            ?
                            'border-glass-border-light bg-glass-light backdrop-blur-md shadow-[inset_0_1px_0px_rgba(255,255,255,0.6),0_0_9px_rgba(0,0,0,0.15),0_15px_30px_rgba(0,0,0,0.12)] dark:border-glass-border-dark dark:bg-glass-dark dark:shadow-[inset_0_1px_0px_rgba(255,255,255,0.08),0_0_9px_rgba(0,0,0,0.4),0_15px_30px_rgba(0,0,0,0.35)]' :
                            'border-zinc-200 bg-white shadow-lg dark:border-zinc-800 dark:bg-dark-primary'"
                        class="z-100 origin-top-right overflow-hidden rounded-2xl border" id="notification-dropdown"
                        wire:ignore.self>

                        {{-- Header --}}
                        <div
                            class="border-glass-divider-light dark:border-glass-divider-dark flex items-center justify-between border-b px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full bg-red-500 shadow-[0_0_6px_rgba(239,68,68,0.6)]">
                                </div>
                                <p class="text-glass-text-light dark:text-glass-text-dark text-sm font-bold">Notifikasi
                                </p>
                            </div>
                        </div>

                        {{-- Notification List --}}
                        <div class="max-h-72 overflow-y-auto md:max-h-96" id="notificationContainer">
                            <livewire:utils.notification-dropdown />
                        </div>

                        {{-- Footer --}}
                        <div class="border-glass-divider-light dark:border-glass-divider-dark border-t px-4 py-3">
                            <a class="text-sm font-semibold text-red-500 transition-colors hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                                href="{{ route('notifications.index') }}">
                                Lihat semua notifikasi →
                            </a>
                        </div>
                    </div>
                </template>
            </div>
            {{-- End Notification --}}

            {{-- Profile --}}
            <div id="profile-content" class="relative" x-data="{
                open: false,
                dropdownStyle: 'top:0;right:0;',
                updatePosition() {
                    const btn = document.getElementById('user-menu-button');
                    if (!btn) return;
                    const rect = btn.getBoundingClientRect();
                    const right = window.innerWidth - rect.right;
                    const top = rect.bottom + 24;
                    this.dropdownStyle = `position:fixed;top:${top}px;right:${right}px;`;
                }
            }" @click.outside="open = false">
                <button @click="open = !open; if(open) updatePosition()"
                    class="flex rounded-full ring-2 ring-red-600 transition-all hover:ring-red-700"
                    id="user-menu-button" type="button" :aria-expanded="open.toString()">
                    <span class="sr-only">Open user menu</span>
                    <img class="h-9 w-9 rounded-full object-cover"
                        src="{{ auth()->user()->profile_pic ? asset('storage/profile-pictures/' . auth()->user()->profile_pic) : asset('images/defaults/profile-picture-5.jpg') }}"
                        alt="user photo" loading="lazy"
                        onerror="this.src = '{{ asset('images/defaults/noImage.webp') }}'">
                </button>

                {{-- Profile Dropdown (teleported to body to escape nav backdrop-filter context) --}}
                <template x-teleport="body">
                    <div x-show="open" @click.outside="open = false" style="display: none;"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        :style="dropdownStyle"
                        x-bind:class="dynamicBg
                            ?
                            'border-glass-border-light bg-glass-light backdrop-blur-md shadow-[inset_0_1px_0px_rgba(255,255,255,0.6),0_0_9px_rgba(0,0,0,0.15),0_15px_30px_rgba(0,0,0,0.12)] dark:border-glass-border-dark dark:bg-glass-dark dark:shadow-[inset_0_1px_0px_rgba(255,255,255,0.08),0_0_9px_rgba(0,0,0,0.4),0_15px_30px_rgba(0,0,0,0.35)]' :
                            'border-zinc-200 bg-white shadow-lg dark:border-zinc-800 dark:bg-dark-primary'"
                        class="z-100 w-60 origin-top-right transform-gpu overflow-hidden rounded-2xl border p-1 transition-all duration-300"
                        id="profile-dropdown">
                        <div class="relative z-10">
                            {{-- User Info --}}
                            <div
                                class="border-glass-divider-light dark:border-glass-divider-dark flex items-center gap-3 border-b px-4 py-3.5">
                                <img class="ring-glass-border-light dark:ring-glass-border-dark h-9 w-9 shrink-0 rounded-full object-cover ring-2"
                                    src="{{ auth()->user()->profile_pic ? asset('storage/profile-pictures/' . auth()->user()->profile_pic) : asset('images/defaults/profile-picture-5.jpg') }}"
                                    alt="user photo" loading="lazy"
                                    onerror="this.src = '{{ asset('images/defaults/noImage.webp') }}'">
                                <div class="min-w-0">
                                    <p
                                        class="text-glass-text-light dark:text-glass-text-dark truncate text-sm font-bold">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-glass-muted-light dark:text-glass-muted-dark truncate text-xs">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <ul class="py-1.5" aria-labelledby="dropdown-item">
                                <li>
                                    <a class="text-glass-text-light hover:bg-glass-hover-light dark:text-glass-text-dark dark:hover:bg-glass-hover-dark flex items-center gap-2.5 px-4 py-2 text-sm font-medium transition-colors hover:text-zinc-900 dark:hover:text-white"
                                        href="{{ route('profile.me') }}">
                                        My Profile
                                    </a>
                                </li>
                                <li>
                                    <form id="editProfile" action="{{ route('profile.edit') }}"
                                        onclick="event.preventDefault();"></form>
                                    <button
                                        class="text-glass-text-light hover:bg-glass-hover-light dark:text-glass-text-dark dark:hover:bg-glass-hover-dark flex w-full items-center gap-2.5 px-4 py-2 text-left text-sm font-medium transition-colors hover:text-zinc-900 dark:hover:text-white"
                                        form="editProfile" type="submit">
                                        Account Settings
                                    </button>
                                </li>
                                @hasanyrole(['Admin', 'HRD', 'Management', 'Management-PKU', 'Management-JKT',
                                    'Management-Special'])
                                    <li>
                                        <livewire:utils.update-log />
                                    </li>
                                @endhasanyrole
                                <li>
                                    <div class="flex items-center justify-between px-4 py-2.5">
                                        <span
                                            class="text-glass-text-light dark:text-glass-text-dark text-sm font-medium">Theme</span>
                                        <div class="flex items-center gap-1.5">
                                            <x-button.dark />
                                            <x-button.light />
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="hover:bg-glass-hover-light dark:hover:bg-glass-hover-dark flex cursor-pointer items-center justify-between px-4 py-2.5 transition-colors"
                                        @click.stop="dynamicBg = !dynamicBg">
                                        <span
                                            class="text-glass-text-light dark:text-glass-text-dark text-sm font-medium">Dynamic
                                            Bg</span>
                                        <button type="button"
                                            class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900/75"
                                            role="switch" :aria-checked="dynamicBg.toString()">
                                            <span class="sr-only">Toggle dynamic background</span>
                                            <span aria-hidden="true"
                                                x-bind:class="dynamicBg ? 'bg-red-500' : 'bg-zinc-300 dark:bg-white/20'"
                                                class="pointer-events-none absolute mx-auto h-4 w-8 rounded-full transition-colors duration-200 ease-in-out"></span>
                                            <span aria-hidden="true"
                                                x-bind:class="dynamicBg ? 'translate-x-4' : 'translate-x-0'"
                                                class="pointer-events-none absolute left-0.5 inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out"></span>
                                        </button>
                                    </div>
                                </li>
                            </ul>

                            {{-- Sign Out & Install --}}
                            <ul class="border-glass-divider-light dark:border-glass-divider-dark border-t py-1.5"
                                aria-labelledby="dropdown-item">
                                <li>
                                    <form id="logout" method="post" action="{{ route('logout') }}"
                                        onclick="event.preventDefault();">@csrf</form>
                                    <button
                                        class="flex w-full items-center gap-2.5 px-4 py-2 text-left text-sm font-medium text-red-500 transition-colors hover:bg-red-500/10 hover:text-red-600 dark:text-red-400 dark:hover:bg-red-500/10 dark:hover:text-red-300"
                                        form="logout" type="submit">
                                        Sign Out
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </template>
            </div>
            {{-- End Profile --}}

        </div>
    </div>
</nav>
