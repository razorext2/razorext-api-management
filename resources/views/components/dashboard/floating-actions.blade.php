{{-- Goal: Unified floating actions container for bottom-right FABs and scroll-to-top, Livewire: None, Alpine: scrollToggle() --}}
{{-- Kontainer ini fixed di bottom-right. Slot dirender di atas scroll-to-top via flex-col-reverse. --}}
{{-- overflow: visible agar item absolute (radial FAB) bisa keluar batas container. --}}
<div class="fixed bottom-24 right-4 z-50 flex flex-col-reverse items-end gap-3 md:bottom-8 md:right-8"
    style="overflow: visible;">

    {{-- Scroll to Top Button (selalu paling bawah) --}}
    <div x-data="scrollToggle()" x-init="init()">
        <a href="javascript:void(0)" @click="handleScroll" x-data="{ tapping: false }" x-on:mousedown="tapping = true"
            x-on:touchstart="tapping = true" x-on:animationend="tapping = false"
            :class="[
                tapping ? 'is-tapping' : '',
                dynamicBg ?
                'bg-glass-light border-glass-border-light backdrop-blur-md shadow-md hover:bg-glass-hover-light dark:bg-glass-dark dark:border-glass-border-dark dark:hover:bg-glass-hover-dark dark:shadow-none' :
                'bg-white border-zinc-200 shadow-sm hover:bg-zinc-50 dark:bg-dark-primary dark:border-zinc-800 dark:hover:bg-zinc-800'
            ]"
            class="liquid-btn group flex h-11 w-11 items-center justify-center rounded-full border transition-[background-color,border-color,box-shadow] duration-300 ease-out"
            x-cloak>
            <span :class="atTop ? 'rotate-0' : 'rotate-180'"
                class="inline-block transition-transform duration-300 group-hover:scale-110">
                <x-icons.carred-down class="h-6 w-6 text-red-600 dark:text-red-500" id="scroll-to-top-icon" />
            </span>
        </a>
    </div>

    {{-- Slot: item tambahan (report approval FABs, leave popup, dll) --}}
    {{ $slot }}

</div>
