{{-- Goal: Render page title with static vertical red accent and inline breadcrumb beneath it, Title centering dynamically on breadcrumb scroll --}}
@php
    $titles = [
        'dashboard' => 'Dashboard',
        'profile.*' => 'Profile',
        'log.*' => 'Log Aktivitas',
        'users.*' => 'Users',
        'roles.*' => 'Roles',
        'permissions.*' => 'Permissions',
        'notifications.*' => 'Pusat Notifikasi',
        'api-clients.*' => 'API Clients & Gateway',
        'sandbox.*' => 'Interactive Sandbox',
        'settings.*' => 'Pengaturan Website',
    ];

    $pageTitle =
        collect($titles)->first(function ($title, $key) {
            return Route::is($key);
        }) ?? 'Default Title';
@endphp

<div x-data="{
    isSticky: false,
    init() {
        const checkScroll = () => {
            this.isSticky = window.scrollY > 30;
        };
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    }
}" class="mb-6 sm:mb-8 flex items-stretch gap-4 h-13.5 sm:h-15.5">
    {{-- Signature Vertical Accent (Fixed size, stretches to match parent height which is constant) --}}
    <div class="w-1.5 rounded-full bg-red-600 shadow-[0_0_20px_rgba(220,38,38,0.4)] dark:bg-red-500"></div>

    {{-- Title and Breadcrumb Inner Flex Column --}}
    <div class="flex flex-col justify-center h-full">
        <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white sm:text-4xl leading-none">
            {{ $pageTitle }}
        </h1>

        {{-- Smooth height, opacity, and margin transitions wrapper --}}
        <div :style="isSticky ? 'height: 0px; opacity: 0; margin-top: 0px;' : 'height: 16px; opacity: 1; margin-top: 6px;'"
             class="transition-all duration-300 ease-in-out overflow-hidden w-full flex items-center">
            <livewire:utils.breadcrumb />
        </div>
    </div>
</div>
