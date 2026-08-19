{{-- Goal: Enhanced Reusable Accordion Item, Livewire: None, Alpine: open --}}
@props(['id', 'title' => '', 'description' => null, 'iconColor' => 'blue', 'expanded' => false])

<div x-data="{ open: @js($expanded) }"
    {{ $attributes->merge(['class' => 'overflow-hidden shadow-md rounded-xl border border-zinc-200 bg-white/60 backdrop-blur-md dark:border-zinc-800 dark:bg-dark-primary/60']) }}>

    <h2 id="{{ $id }}-heading" class="m-0">
        <button type="button"
            class="hover: dark:hover: flex w-full items-center justify-between gap-3 p-5 text-left transition-all duration-300 focus:outline-none"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
            @click="open = !open" :aria-expanded="open" aria-controls="{{ $id }}-body">

            <div class="flex min-w-0 flex-1 items-center gap-4">
                @if (isset($icon))
                    <div @class([
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition-all duration-300',
                        'bg-blue-600 text-white shadow-lg shadow-blue-500/20' =>
                            $iconColor === 'primary' || $iconColor === 'blue',
                        'bg-green-600 text-white shadow-lg shadow-green-500/20' =>
                            $iconColor === 'green',
                        'bg-red-600 text-white shadow-lg shadow-red-500/20' => $iconColor === 'red',
                        'bg-amber-500 text-white shadow-lg shadow-amber-500/20' =>
                            $iconColor === 'amber',
                        'bg-zinc-600 text-white shadow-lg shadow-zinc-500/20' =>
                            $iconColor === 'zinc',
                    ])>
                        {{ $icon }}
                    </div>
                @endif

                <div class="min-w-0 flex-1">
                    <h3 class="m-0 truncate text-base font-bold tracking-tight text-gray-800 dark:text-white">
                        {{ $title }}
                    </h3>
                    @if ($description)
                        <p class="m-0 mt-0.5 truncate text-xs font-medium text-zinc-500 dark:text-zinc-400">
                            {{ $description }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 transition-all duration-300 dark:bg-zinc-800"
                :class="open ? 'rotate-180 bg-blue-50 dark:bg-blue-950/30' : ''">
                <x-icons.carred-down class="h-4 w-4 text-zinc-500 dark:text-zinc-400" />
            </div>
        </button>
    </h2>

    <div id="{{ $id }}-body" x-show="open" x-collapse aria-labelledby="{{ $id }}-heading" x-cloak
        wire:ignore.self>
        <div class="border-t border-zinc-200 p-4 transition-all duration-500 dark:border-zinc-800 lg:p-6">
            {{ $slot }}
        </div>
    </div>
</div>
