@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4" role="navigation">
        <p class="text-xs text-zinc-500 dark:text-zinc-400">
            Menampilkan
            <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $paginator->firstItem() }}</span>
            -
            <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $paginator->lastItem() }}</span>
        </p>

        <div class="flex items-center gap-2">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="flex h-9 items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-4 text-xs font-bold text-zinc-300 dark:border-zinc-800 dark:text-zinc-700"
    x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                    <x-icons.chevron-left class="h-4 w-4" />
                    Sebelumnya
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="flex h-9 items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 text-xs font-bold text-zinc-600 transition-all hover:border-red-500 hover:text-red-600 dark:border-zinc-800 dark:bg-dark-primary dark:text-zinc-400 dark:hover:border-red-500">
                    <x-icons.chevron-left class="h-4 w-4" />
                    Sebelumnya
                </button>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="flex h-9 items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 text-xs font-bold text-zinc-600 transition-all hover:border-red-500 hover:text-red-600 dark:border-zinc-800 dark:bg-dark-primary dark:text-zinc-400 dark:hover:border-red-500">
                    Selanjutnya
                    <x-icons.chevron-right class="h-4 w-4" />
                </button>
            @else
                <span class="flex h-9 items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-4 text-xs font-bold text-zinc-300 dark:border-zinc-800 dark:text-zinc-700"
    x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                    Selanjutnya
                    <x-icons.chevron-right class="h-4 w-4" />
                </span>
            @endif
        </div>
    </nav>
@endif
