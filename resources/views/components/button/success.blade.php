{{-- Goal: Liquid glass success button (positive/complete actions), Smart Tag (a/button), Livewire: -, Alpine: jelly-tap --}}
@props([
    'icon'     => null,
    'type'     => 'button',
    'id'       => null,
    'href'     => null,
    'iconOnly' => false,
    'pill'     => false,
])

<x-button.liquid-glass color="emerald" :icon="$icon" :type="$type" :id="$id" :href="$href" :iconOnly="$iconOnly" :pill="$pill" {{ $attributes }}>
    {{ $slot }}
</x-button.liquid-glass>
