{{-- Goal: Render a standard Nonaktif status badge, Livewire: -, Alpine: - --}}
@props(['is_active' => false])

@if (!$is_active)
    <span
        class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-[9px] font-medium text-red-600 ring-1 ring-inset ring-red-500/20 dark:bg-red-900/20 dark:text-red-400">
        Nonaktif
    </span>
@endif
