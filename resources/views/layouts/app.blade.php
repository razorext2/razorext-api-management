{{-- Goal: Full Livewire v4 & Blade Master Layout, Livewire: Yes, Alpine: Yes --}}
<!DOCTYPE html>
<html class="{{ isset($_COOKIE['color-theme']) && $_COOKIE['color-theme'] === 'dark' ? 'dark' : '' }}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.head')
</head>

<body id="container" class="relative bg-[#faf8f5] text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100"
    x-data="{ openSidebar: true, menuSearch: '', dynamicBg: localStorage.getItem('dynamicBg') === null ? false : localStorage.getItem('dynamicBg') === 'true' }" x-init="$watch('dynamicBg', value => localStorage.setItem('dynamicBg', value));" :class="{ 'no-blur': !dynamicBg }">

    <div x-show="dynamicBg" x-transition.opacity.duration.300ms>
        <x-utils.dynamic-background />
    </div>

    <div class="relative z-10 mb-5 flex min-h-screen flex-col">
        @if (session('status'))
            <x-alert.popup>
                {{ session('status') }}
            </x-alert.popup>
        @endif

        @include('layouts.partials.navbar')

        @include('layouts.partials.sidebar')

        <div :class="openSidebar ? 'md:ml-72' : ''"
            class="md:mt-30 mb-20 mt-28 px-4 transition-[margin-left] duration-300 ease-in-out will-change-transform md:mb-4 xl:px-8">

            {{-- Title --}}
            @include('layouts.partials.title')

            {{-- Announcement --}}
            <livewire:utils.announcement-container />

            <x-utils.offline-alert class="mb-2" />

            {{-- Main Content Container (Supports Livewire v4 $slot & Blade @yield('content')) --}}
            <div class="min-h-0 flex-1">
                @if (isset($slot) && $slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>

        </div>

    </div>

    {{-- Mobile Navigation Drawer --}}
    @persist('mobile-drawer')
        <x-drawer.mobile-menu />
    @endpersist

    {{-- Preloader --}}
    @persist('preloader')
        <x-utils.preloader x-show="dynamicBg" />
    @endpersist

    {{-- Floating Actions Stack --}}
    <x-dashboard.floating-actions>
    </x-dashboard.floating-actions>

    {{-- Sudo Mode Verification Modal --}}
    <livewire:utils.sudo-modal />

    <!-- JavaScript -->
    @include('layouts.partials.js')
    @stack('modals')
</body>


</html>
