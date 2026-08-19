{{-- Goal: Tombol Tab untuk navigasi panel (Premium Line Style), Livewire: -, Alpine: - --}}
@props(['active' => false, 'icon' => null, 'id' => null])

@php
    $baseClasses =
        'group relative inline-flex items-center justify-center gap-2 px-1 py-4 text-sm font-medium transition-colors duration-300 focus:outline-none';

    // State Colors
    $classes =
        $baseClasses .
        ' text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' . // Inactive / Hover
        ' aria-selected:text-red-600 dark:aria-selected:text-red-500 aria-selected:border-b-2 aria-selected:border-red-600 dark:aria-selected:border-red-500'; // Active
@endphp

<button id="{{ $id }}" type="button" role="tab" aria-selected="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => $classes]) }}>

    @if ($icon)
        <span class="relative z-10 inline-flex items-center">{{ $icon }}</span>
    @endif

    @if ($slot->isNotEmpty())
        <span class="relative z-10">{{ $slot }}</span>
    @endif
</button>
