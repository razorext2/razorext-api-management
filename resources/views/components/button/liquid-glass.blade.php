{{-- Goal: Base liquid glass button — shared structure for all button variants, Smart Tag (a/button), Livewire: -, Alpine: jelly-tap --}}
@props([
    'icon' => null,
    'type' => 'button',
    'id' => null,
    'href' => null,
    'iconOnly' => false,
    'pill' => null,
    'color' => 'zinc',
])

@php
    $colorMap = [
        'red' => [
            'light' =>
                'border border-white/20 bg-red-600/90 text-white shadow-md shadow-red-500/20 hover:bg-red-600/95 hover:border-white/30 hover:shadow-lg hover:shadow-red-500/30 active:bg-red-700',
            'dark' =>
                'dark:border-red-500/30 dark:bg-red-600/20 dark:text-red-200 dark:shadow-red-500/15 dark:backdrop-blur-md dark:hover:border-red-400/40 dark:hover:bg-red-500/30 dark:hover:text-red-100',
            'ring' => 'focus-visible:ring-red-400/40',
        ],
        'blue' => [
            'light' =>
                'border border-white/20 bg-blue-500/90 text-white shadow-md shadow-blue-500/20 hover:bg-blue-600/95 hover:border-white/30 hover:shadow-lg hover:shadow-blue-500/30 active:bg-blue-700',
            'dark' =>
                'dark:border-blue-500/30 dark:bg-blue-500/20 dark:text-blue-200 dark:shadow-blue-500/15 dark:backdrop-blur-md dark:hover:border-blue-400/40 dark:hover:bg-blue-500/30 dark:hover:text-blue-100',
            'ring' => 'focus-visible:ring-blue-400/40',
        ],
        'emerald' => [
            'light' =>
                'border border-white/20 bg-emerald-500/90 text-white shadow-md shadow-emerald-500/20 hover:bg-emerald-600/95 hover:border-white/30 hover:shadow-lg hover:shadow-emerald-500/30 active:bg-emerald-700',
            'dark' =>
                'dark:border-emerald-500/30 dark:bg-emerald-500/20 dark:text-emerald-200 dark:shadow-emerald-500/15 dark:backdrop-blur-md dark:hover:border-emerald-400/40 dark:hover:bg-emerald-500/30 dark:hover:text-emerald-100',
            'ring' => 'focus-visible:ring-emerald-400/40',
        ],
        'amber' => [
            'light' =>
                'border border-white/20 bg-amber-500/90 text-white shadow-md shadow-amber-500/20 hover:bg-amber-600/95 hover:border-white/30 hover:shadow-lg hover:shadow-amber-500/30 active:bg-amber-700',
            'dark' =>
                'dark:border-amber-500/30 dark:bg-amber-500/20 dark:text-amber-200 dark:shadow-amber-500/15 dark:backdrop-blur-md dark:hover:border-amber-400/40 dark:hover:bg-amber-500/30 dark:hover:text-amber-100',
            'ring' => 'focus-visible:ring-amber-400/40',
        ],
        'zinc' => [
            'light' =>
                'border border-zinc-200 bg-white text-zinc-700 shadow-sm hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-900 active:bg-zinc-50',
            'dark' =>
                'dark:border-zinc-800 dark:bg-zinc-800/60 dark:text-zinc-300 dark:shadow-black/40 dark:backdrop-blur-md dark:hover:border-zinc-700 dark:hover:bg-zinc-700/60 dark:hover:text-zinc-100 dark:active:bg-zinc-700/80',
            'ring' => 'focus-visible:ring-zinc-400/40',
        ],
    ];

    $palette = $colorMap[$color] ?? $colorMap['zinc'];
    $tag = $href ? 'a' : 'button';
    $isPill = $pill ?? $iconOnly;
    $radius = $isPill ? 'rounded-full' : 'rounded-lg';
    $sizing = $iconOnly ? 'h-9 w-9 p-0' : 'px-4 py-2 gap-2';

    $passedClass = $attributes->get('class', '');
    $hasCustomPosition =
        str_contains($passedClass, 'absolute') ||
        str_contains($passedClass, 'fixed') ||
        str_contains($passedClass, 'static') ||
        str_contains($passedClass, 'sticky');
    $positionClass = $hasCustomPosition ? '' : 'relative';

    $baseClasses = implode(' ', [
        "liquid-btn group {$positionClass} inline-flex items-center justify-center overflow-hidden {$radius} {$sizing} transform-gpu antialiased",
        $palette['light'],
        $palette['dark'],
        'font-medium text-sm transition-colors duration-150 ease-out no-underline',
        "focus:outline-none focus-visible:ring-2 {$palette['ring']} focus-visible:ring-offset-1 focus-visible:ring-offset-white dark:focus-visible:ring-offset-zinc-950",
        'disabled:pointer-events-none disabled:opacity-40 disabled:!animate-none',
    ]);
@endphp

<{{ $tag }} id="{{ $id }}" {{ $href ? "href=$href" : "type=$type" }} x-data="{ tapping: false }"
    x-on:mousedown="tapping = true" x-on:touchstart="tapping = true" x-on:animationend="tapping = false"
    x-on:animationcancel="tapping = false" :class="{ 'is-tapping': tapping }"
    {{ $attributes->merge(['class' => $baseClasses]) }}>

    {{-- Layer 1: top edge highlight (dark mode only) --}}
    <span
        class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 dark:opacity-100"></span>

    {{-- Layer 2: inner shadow --}}
    <span
        class="{{ $radius }} pointer-events-none absolute inset-0 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),inset_0_-1px_2px_rgba(0,0,0,0.05)] dark:shadow-[inset_0_1px_1px_rgba(255,255,255,0.05),inset_0_-1px_2px_rgba(0,0,0,0.3)]"></span>

    {{-- Layer 3: shine sweep (dark hover only) --}}
    <span
        class="pointer-events-none absolute -inset-y-2 -left-1/2 w-1/4 rotate-12 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 transition-all duration-700 ease-out group-hover:left-[130%] dark:group-hover:opacity-100"></span>

    {{-- Content --}}
    @if ($icon)
        <span class="relative z-10 shrink-0 transform-gpu [&>svg]:h-4 [&>svg]:w-4">{{ $icon }}</span>
    @endif
    @if ($slot->isNotEmpty())
        <span class="relative z-10 transform-gpu whitespace-nowrap">{!! trim($slot) !!}</span>
    @endif
    </{{ $tag }}>
