{{-- Goal: Mobile navigation bottom bar, Livewire: none, Alpine: yes (inherits dynamicBg from layout) --}}
<div {{ $attributes }} class="fixed bottom-3 left-1/2 z-[160] w-[92vw] max-w-sm -translate-x-1/2 md:hidden">
    <!-- iOS Glass Navigation Container -->
    <div x-bind:class="dynamicBg
        ?
        'bg-glass-light border-glass-border-light backdrop-blur-md shadow-[0_8px_30px_rgba(0,0,0,0.08)] dark:bg-glass-dark dark:border-glass-border-dark dark:shadow-[0_8px_30px_rgba(0,0,0,0.4)]' :
        'bg-white border-zinc-200/60 shadow-[0_8px_30px_rgba(0,0,0,0.04)] dark:bg-dark-primary dark:border-zinc-800/60 dark:shadow-zinc-950/50'"
        class="h-[70px] w-full rounded-full border transition-colors duration-300" x-cloak>
        <div class="mx-auto grid h-full max-w-sm grid-cols-5 px-3">

            <x-drawer.button href="{{ route('dashboard') }}" :label="'Home'" :active="Route::is('dashboard')">
                <x-icons.home
                    class="{{ Route::is('dashboard') ? 'text-red-600 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }} h-6 w-6 transition-all duration-300 group-hover:text-red-500 dark:group-hover:text-red-400" />
            </x-drawer.button>

            <x-drawer.button href="{{ Route::has('attendanceIn.index') ? route('attendanceIn.index') : '#' }}" :label="'Masuk'" :active="Route::is('attendanceIn.index')">
                <x-icons.arrow-left-bracket
                    class="{{ Route::is('attendanceIn.index') ? 'text-red-600 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }} h-6 w-6 transition-all duration-300 group-hover:text-red-500 dark:group-hover:text-red-400" />
            </x-drawer.button>

            <!-- Center Contained Action Button -->
            <div class="relative flex items-center justify-center" x-data="{
                isOpen: false,
                init() {
                    const drawer = document.getElementById('drawer-swipe');
                    if (!drawer) return;
                    const observer = new MutationObserver(() => {
                        this.isOpen = !drawer.classList.contains('translate-y-full');
                    });
                    observer.observe(drawer, { attributes: true, attributeFilter: ['class'] });
                }
            }">
                <button x-data="{ tapping: false }" x-on:mousedown="tapping = true" x-on:touchstart="tapping = true"
                    x-on:animationend="tapping = false" x-on:animationcancel="tapping = false"
                    :class="[
                        tapping ? 'is-tapping' : '',
                        isOpen ?
                        'shadow-[0_12px_28px_-4px_rgba(220,38,38,0.6)] from-red-700 to-red-600' :
                        'hover:shadow-[0_12px_25px_-4px_rgba(220,38,38,0.5)]'
                    ]"
                    class="liquid-btn group flex h-[52px] w-[52px] items-center justify-center rounded-full bg-gradient-to-tr from-red-600 to-red-500 shadow-[0_8px_20px_-4px_rgba(220,38,38,0.4)] transition-all duration-300 ease-out will-change-transform"
                    data-drawer-target="drawer-swipe" data-drawer-toggle="drawer-swipe" data-drawer-placement="bottom"
                    data-drawer-backdrop="false" data-drawer-edge="true" data-drawer-edge-offset="-bottom-[6rem]"
                    type="button" aria-controls="drawer-swipe">
                    <x-icons.bar :class="'h-6 w-6 text-white transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]'" ::class="isOpen ? 'rotate-90' : 'rotate-0'" />
                    <span class="sr-only">Menu Drawer</span>
                </button>
            </div>

            <x-drawer.button href="{{ Route::has('attendanceOut.index') ? route('attendanceOut.index') : '#' }}" :label="'Keluar'" :active="Route::is('attendanceOut.index')">
                <x-icons.arrow-right-bracket
                    class="{{ Route::is('attendanceOut.index') ? 'text-red-600 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }} h-6 w-6 transition-all duration-300 group-hover:text-red-500 dark:group-hover:text-red-400" />
            </x-drawer.button>

            <x-drawer.button href="{{ route('profile.edit') }}" :label="'Profile'" :active="Route::is('profile.edit')">
                <x-icons.profile-card
                    class="{{ Route::is('profile.*') ? 'text-red-600 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }} h-6 w-6 transition-all duration-300 group-hover:text-red-500 dark:group-hover:text-red-400" />
            </x-drawer.button>

        </div>
    </div>
</div>
