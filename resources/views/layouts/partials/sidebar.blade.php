{{-- Goal: Sidebar navigation layout with responsive display controls, Livewire: None, Alpine: Yes --}}
@php
    $rawMenu = config('navigation');
    $menu = [];
    $tempHeader = null;

    foreach ($rawMenu as $item) {
        // ── Header Handling ──────────────────────────────────
        if (($item['type'] ?? '') === 'header') {
            $tempHeader = $item;
            continue;
        }

        // ── Permission Check ─────────────────────────────────
        $guard = $item['guard'] ?? null;
        $canSee = match (true) {
            $guard === null => true,
            ($guard[0] ?? '') === 'any_permission' => auth()->user()->hasAnyPermission($guard[1]),
            ($guard[0] ?? '') === 'role' => auth()->user()->hasRole($guard[1]),
            ($guard[0] ?? '') === 'can' => auth()->user()->can($guard[1]),
            default => true,
        };

        if ($canSee) {
            // ── Sub-menu Check (for groups) ──────────────────
            if (!empty($item['submenu'] ?? [])) {
                $hasVisibleSub = false;
                foreach ($item['submenu'] as $sub) {
                    $perm = $sub['permission'] ?? null;
                    if (
                        match (true) {
                            $perm === null => true,
                            is_array($perm) => auth()->user()->hasAnyPermission($perm),
                            default => auth()->user()->can($perm),
                        }
                    ) {
                        $hasVisibleSub = true;
                        break;
                    }
                }
                if (!$hasVisibleSub) {
                    continue;
                }
            }

            // ── Push Header if pending ───────────────────────
            if ($tempHeader) {
                $menu[] = $tempHeader;
                $tempHeader = null;
            }

            $menu[] = $item;
        }
    }
@endphp

<!-- Sidebar Navigation -->
<aside
    :class="[
        openSidebar ? 'translate-x-0' : '-translate-x-80',
        dynamicBg ?
        'bg-glass-light border-glass-border-light backdrop-blur-md shadow-md dark:bg-glass-dark dark:border-glass-border-dark' :
        'bg-white border-zinc-200 shadow-sm dark:bg-dark-primary dark:border-zinc-800'
    ]"
    class="fixed bottom-4 left-4 top-4 z-40 hidden w-68 flex-col rounded-2xl border pb-6 transition-transform duration-300 ease-in-out will-change-transform md:flex"
    x-cloak id="logo-sidebar" aria-label="Sidebar">

    {{-- Header / Toggle --}}
    <div id="tombolSidebar"
        class="mx-auto flex w-full items-center justify-between border-b border-zinc-200/50 p-5 dark:border-zinc-800/50">
        <div class="flex items-center justify-start pl-5">
            <a class="flex items-center gap-2.5" href="{{ config('app.url') }}">
                <img class="h-8 w-8 rounded-lg object-contain" src="{{ setting('logo_path') ? asset('storage/' . setting('logo_path')) : asset('images/brand/logo.png') }}"
                    alt="{{ setting('site_name', 'RazorAPI') }} Logo" loading="lazy" />
                <span class="text-lg font-bold italic tracking-wide text-zinc-900 dark:text-white">
                    {{ setting('sidebar_title', 'RazorAPI') }}
                </span>
            </a>
        </div>

    </div>

    {{-- Search Bar --}}
    <div class="px-5 pt-4" x-show="openSidebar" x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="group relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-icons.search
                    class="h-4 w-4 text-zinc-400 transition-colors duration-200 group-focus-within:text-red-600" />
            </div>
            <input type="text" x-model="menuSearch"
                class="block w-full rounded-xl border-zinc-200 bg-zinc-50/50 py-2.5 pl-10 pr-3 text-sm tracking-wide text-zinc-900 placeholder-zinc-400 transition-colors duration-200 focus:border-red-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-600/20 dark:border-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:ring-red-500/20"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
                placeholder="Cari Menu..." />
            <button x-show="menuSearch" @click="menuSearch = ''"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-red-500">
                <x-icons.close class="h-4 w-4" />
            </button>
        </div>
    </div>

    {{-- Navigation Links --}}
    <div id="sidebar-scroll" class="overflow-x-hidden overflow-y-scroll p-5 pb-10" style="overflow-anchor: none" x-data
        x-init="const saved = sessionStorage.getItem('sidebar-scroll');
        if (saved) $el.scrollTop = parseInt(saved);
        $el.addEventListener('scroll', () => sessionStorage.setItem('sidebar-scroll', $el.scrollTop), { passive: true });
        document.addEventListener('livewire:navigating', () => sessionStorage.setItem('sidebar-scroll', $el.scrollTop));">
        <ul class="space-y-2 font-medium">
            @foreach ($menu as $item)
                @if (($item['type'] ?? '') === 'header')
                    {{-- ── Header / Spacer ────────────────────────────────── --}}
                    <li x-show="!menuSearch || '{{ strtolower($item['label']) }}'.includes(menuSearch.toLowerCase())"
                        class="px-4 py-2">
                        <span
                            class="text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 dark:text-zinc-500">
                            {{ $item['label'] }}
                        </span>
                    </li>
                @elseif (empty($item['submenu'] ?? []))
                    {{-- ── Simple link ────────────────────────────────── --}}
                    @php
                        $isActive = collect($item['check'])->contains(fn($r) => Route::is($r));
                    @endphp
                    <li x-show="!menuSearch || '{{ strtolower($item['label']) }}'.includes(menuSearch.toLowerCase())">
                        <a href="{{ route($item['route']) }}"
                            class="{{ $isActive ? 'bg-zinc-100/80 dark:bg-white/5 text-red-600 dark:text-red-400 font-bold border-l-4 border-red-600' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 hover:text-zinc-900 dark:hover:text-zinc-200 border-l-4 border-transparent' }} group relative flex items-center gap-3.5 rounded-r-2xl px-4 py-3 transition-all duration-200"
                            {{ $item['navigate'] ?? true ? 'wire:navigate' : '' }}>

                            <x-dynamic-component :component="'icons.' . $item['icon']"
                                class="{{ $isActive ? 'text-red-600' : 'text-zinc-400 group-hover:text-red-600' }} h-5 w-5 shrink-0 transition-colors duration-200" />

                            <div class="flex flex-1 items-center justify-between overflow-hidden">
                                <span
                                    class="whitespace-normal warp-break-word text-sm tracking-wide transition-colors duration-200">
                                    {{ $item['label'] }}
                                </span>

                                @if ($item['counter'] ?? null)
                                    <div class="shrink-0">
                                        @if (!($item['counter_permission'] ?? null) || auth()->user()->can($item['counter_permission']))
                                            <livewire:$item['counter'] />
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @else
                    {{-- ── Group with submenu ──────────────────────────── --}}
                    @php
                        // Derive active routes and search terms
                        $groupRoutes = collect($item['submenu'])->pluck('check')->flatten()->all();
                        $searchTerms = collect($item['submenu'])
                            ->pluck('label')
                            ->push($item['label'])
                            ->map(fn($l) => strtolower($l))
                            ->toJson();
                    @endphp

                    <x-dashboard.sidebar-group :label="$item['label']" :icon="$item['icon']" :routes="$groupRoutes" :search-terms="$searchTerms">

                        @foreach ($item['submenu'] as $sub)
                            @php
                                $perm = $sub['permission'] ?? null;
                                $subCan = match (true) {
                                    $perm === null => true,
                                    is_array($perm) => auth()->user()->hasAnyPermission($perm),
                                    default => auth()->user()->can($perm),
                                };
                            @endphp

                            @if ($subCan)
                                <x-dashboard.sidebar-sublink :href="route($sub['route'])" :icon="$sub['icon']" :check="$sub['check']"
                                    :navigate="$sub['navigate'] ?? true" :counter="($sub['counter'] ?? null) &&
                                    (!($sub['counter_permission'] ?? null) ||
                                        auth()->user()->can($sub['counter_permission']))
                                        ? $sub['counter']
                                        : null">
                                    {{ $sub['label'] }}
                                </x-dashboard.sidebar-sublink>
                            @endif
                        @endforeach

                    </x-dashboard.sidebar-group>
                @endif
            @endforeach
        </ul>
    </div>

    <!-- start footer -->
    @include('layouts.partials.footer')
    <!-- footer -->
</aside>
