{{-- Goal: Popover message component with trigger button, Livewire: None, Alpine: None --}}
@props([
    'id',
    'placement' => 'bottom-start',
])
<x-button.secondary data-popover-target="{{ $id }}" data-popover-placement="{{ $placement }}" type="button"
    :iconOnly="true"
    class="!h-6 !w-6 !border-0 !bg-transparent !p-0 text-zinc-400 !shadow-none !ring-0 hover:text-zinc-500">
    <x-icons.question-circle class="h-4 w-4" />
    <span class="sr-only">Show information</span>
</x-button.secondary>

<div class="invisible absolute z-[55] inline-block w-72 rounded-xl border border-zinc-200 bg-white text-sm text-zinc-500 opacity-0 shadow-md transition-opacity duration-300 dark:border-zinc-800 dark:bg-zinc-800 dark:text-zinc-400 dark:shadow-none"
    id="{{ $id }}" data-popover role="tooltip">
    <div class="space-y-2 p-3">
        <h3 class="font-semibold text-zinc-900 dark:text-white">Perhatian!</h3>
        <p>
            {{ $slot }}
        </p>
    </div>
    <div data-popper-arrow></div>
</div>
