{{-- Goal: Liquid glass primary button (main call to action), Smart Tag (a/button), Livewire: -, Alpine: jelly-tap --}}
@props([
    'icon'     => null,
    'type'     => 'submit',
    'id'       => null,
    'href'     => null,
    'iconOnly' => false,
    'pill'     => false,
])

<x-button.liquid-glass color="blue" :icon="$icon" :type="$type" :id="$id" :href="$href" :iconOnly="$iconOnly" :pill="$pill" {{ $attributes }}>
    {{ $slot }}
</x-button.liquid-glass>
