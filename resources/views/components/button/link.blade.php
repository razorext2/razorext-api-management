{{-- Goal: Liquid glass link button (navigation anchor), Livewire: -, Alpine: jelly-tap --}}
@props([
    'href'     => '#',
    'icon'     => null,
    'id'       => null,
    'iconOnly' => false,
    'pill'     => false,
])

<x-button.liquid-glass color="zinc" :icon="$icon" :id="$id" :href="$href" :iconOnly="$iconOnly" :pill="$pill" {{ $attributes }}>
    {{ $slot }}
</x-button.liquid-glass>
