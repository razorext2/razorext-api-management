{{-- Goal: Mobile Drawer Menu with dynamic background, Livewire: None, Alpine: dynamicBg scope from parent --}}
<x-drawer.navigation />

{{-- Backdrop overlay — closes drawer when clicking outside --}}
<div x-data="{
    isOpen: false,
    init() {
        const drawer = document.getElementById('drawer-swipe');
        if (!drawer) return;
        const observer = new MutationObserver(() => {
            this.isOpen = !drawer.classList.contains('translate-y-full');
        });
        observer.observe(drawer, { attributes: true, attributeFilter: ['class'] });
    }
}" x-show="isOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="document.querySelector('[data-drawer-toggle=\'drawer-swipe\']').click()"
    class="fixed inset-0 z-[149] bg-zinc-950/20 backdrop-blur-[2px] md:hidden" style="display:none;">
</div>

<!-- Static Wrapper to clip the drawer below the bottom of the pill nav -->
<div
    class="pointer-events-none fixed bottom-3 left-0 right-0 z-[150] mx-auto h-[70vh] w-[92vw] max-w-sm overflow-hidden rounded-[35px] md:hidden">

    <!-- drawer component -->
    <div x-data="{ search: '' }"
        class="pointer-events-auto h-full w-full translate-y-full rounded-[35px] border shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.3)] transition-[transform] duration-[420ms] ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform [backface-visibility:hidden] [transform-style:preserve-3d]"
        x-bind:class="dynamicBg
            ?
            'bg-glass-light border-glass-border-light backdrop-blur-md dark:bg-glass-dark dark:border-glass-border-dark' :
            'bg-white border-zinc-200 dark:bg-dark-primary dark:border-zinc-800'"
        id="drawer-swipe" aria-labelledby="drawer-swipe-label" tabindex="-1">

        <!-- Drag Handle -->
        <div class="cursor-pointer rounded-t-[34px] p-5 transition-colors"
            x-bind:class="dynamicBg
                ?
                'hover:bg-white/10 dark:hover:bg-white/5' :
                'hover:bg-zinc-50 dark:hover:bg-zinc-900'"
            data-drawer-toggle="drawer-swipe">
            <span
                class="absolute left-1/2 top-4 h-1.5 w-12 -translate-x-1/2 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
        </div>

        <!-- Live Search Field -->
        <div class="px-5 pb-4">
            <div class="group relative">
                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-4">
                    <x-icons.search class="h-5 w-5 text-zinc-400 transition-colors group-focus-within:text-red-500" />
                </div>
                <input type="text" x-model="search"
                    class="dark:placeholder-zinc-450 block w-full rounded-lg border-0 bg-zinc-50/50 p-3.5 ps-11 text-sm text-zinc-900 placeholder-zinc-500 ring-1 ring-zinc-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 dark:text-white dark:ring-zinc-800"
                    x-bind:class="dynamicBg ?
                        'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                        'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
                    placeholder="Cari menu...">
            </div>
        </div>

        {{-- Menu Grid + bottom gradient fade --}}
        <div class="relative h-[calc(100%-120px)]">
            <div
                class="custom-scrollbar grid h-full grid-cols-3 gap-x-2 gap-y-6 overflow-y-auto px-4 pb-32 pt-2 md:grid-cols-4">

                @foreach ($drawerLinks as $item)
                    @php
                        // Unified guard check — same pattern as desktop sidebar
                        $guard = $item['guard'] ?? null;
                        $canSee = match (true) {
                            $guard === null => true,
                            $guard[0] === 'any_permission' => auth()->user()->hasAnyPermission($guard[1]),
                            $guard[0] === 'role' => auth()->user()->hasRole($guard[1]),
                            $guard[0] === 'can' => auth()->user()->can($guard[1]),
                            default => true,
                        };

                        $isActive = Route::is($item['check']);
                    @endphp

                    @if ($canSee)
                        <a x-show="search === '' || '{{ addslashes(strtolower($item['label'])) }}'.includes(search.toLowerCase())"
                            x-transition.opacity.duration.300ms x-data="{ tapping: false }" x-on:mousedown="tapping = true"
                            x-on:touchstart="tapping = true" x-on:animationend="tapping = false"
                            x-on:animationcancel="tapping = false" :class="{ 'is-tapping': tapping }"
                            class="liquid-btn {{ $isActive ? 'bg-red-50/50 dark:bg-red-500/10 ring-1 ring-red-200 dark:ring-red-900/50' : 'bg-transparent hover:bg-zinc-100 dark:hover:bg-zinc-800/50' }} group flex cursor-pointer flex-col items-center rounded-xl p-3 transition-all duration-300"
                            href="{{ route($item['link']) }}">

                            <div x-bind:class="{{ $isActive ? 'true' : 'false' }} ? 'bg-red-600 text-white shadow-md shadow-red-500/30' : (
                                dynamicBg ?
                                'bg-white/40 border border-white/20 text-zinc-600 dark:bg-white/5 dark:border-white/10 dark:text-zinc-300 group-hover:bg-white/60 dark:group-hover:bg-white/10 group-hover:text-zinc-900 dark:group-hover:text-white' :
                                'bg-zinc-100 border border-zinc-200 text-zinc-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 group-hover:bg-white dark:group-hover:bg-zinc-700 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 shadow-sm'
                            )"
                                class="mb-3 flex h-14 w-14 items-center justify-center rounded-lg transition-all duration-300 [backface-visibility:hidden] [transform:translate3d(0,0,0)] group-hover:-translate-y-1">
                                <x-dynamic-component :component="'icons.' . $item['icon']"
                                    class="{{ $isActive ? '' : 'group-hover:scale-110' }} h-7 w-7 transition-transform duration-300 [transform:translate3d(0,0,0)]" />
                            </div>

                            <div x-bind:class="{{ $isActive ? 'true' : 'false' }} ? 'text-red-600 dark:text-red-400 font-bold' : (
                                dynamicBg ?
                                'text-zinc-600 dark:text-zinc-300 font-medium group-hover:text-zinc-900 dark:group-hover:text-white' :
                                'text-zinc-600 dark:text-zinc-400 font-medium group-hover:text-zinc-900 dark:group-hover:text-zinc-200'
                            )"
                                class="line-clamp-2 text-center text-xs tracking-tight transition-colors">
                                {{ $item['label'] }}
                            </div>
                        </a>
                    @endif
                @endforeach

                <!-- Empty State -->
                <div x-show="!$el.parentNode.querySelector('a:not([style*=\'display: none\'])')"
                    class="col-span-3 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400"
                    style="display: none;">
                    Menu tidak ditemukan.
                </div>
            </div>

            {{-- Gradient fade — blends last row of grid into the bottom --}}
            <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-28"
                x-bind:class="dynamicBg
                    ?
                    'bg-gradient-to-t from-white/95 to-transparent dark:from-zinc-900/95' :
                    'bg-gradient-to-t from-white to-transparent dark:from-dark-primary'">
            </div>
        </div>
    </div>
</div>
