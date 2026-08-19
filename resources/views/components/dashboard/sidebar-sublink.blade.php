@props(['href', 'icon', 'check' => [], 'navigate' => true, 'counter' => null])

@php
    $checks = is_array($check) ? $check : [$check];
    $isActive = collect($checks)->contains(fn($r) => Route::is($r));
@endphp

<li x-show="!menuSearch || '{{ strtolower($slot) }}'.includes(menuSearch.toLowerCase())"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0">
    <a class="{{ $isActive ? 'text-red-600 dark:text-red-400 font-bold bg-zinc-100/50 dark:bg-white/5' : 'text-zinc-500 dark:text-zinc-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/50' }} group relative flex w-full items-center rounded-xl py-2.5 pl-11 pr-4 transition-all duration-200"
        href="{{ $href }}" {{ $navigate ? 'wire:navigate' : '' }}>

        <div class="flex flex-1 items-center justify-between overflow-hidden">
            <div class="flex items-center gap-3">
                <x-dynamic-component :component="'icons.' . $icon"
                    class="{{ $isActive ? 'text-red-600' : 'text-zinc-400 group-hover:text-red-600' }} h-5 w-5 flex-shrink-0 transition-colors duration-200" />

                <span class="whitespace-normal break-words text-sm transition-colors duration-200">
                    {{ $slot }}
                </span>
            </div>

            @if ($counter)
                <div class="ml-2 flex-shrink-0">
                    <livewire:dynamic-component :component="$counter" />
                </div>
            @endif
        </div>
    </a>
</li>
