@props([
    'href' => '#',
    'label' => '',
    'active' => false,
    'classes' => '',
])

<a {{ $attributes }} x-data="{ tapping: false }" x-on:mousedown="tapping = true" x-on:touchstart="tapping = true"
    x-on:animationend="tapping = false" x-on:animationcancel="tapping = false" :class="{ 'is-tapping': tapping }"
    class="liquid-btn group inline-flex flex-col items-center justify-center px-5"
    href="{{ $href }}">
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
</a>
