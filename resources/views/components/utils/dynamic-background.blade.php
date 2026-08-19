{{-- Goal: Ultra-lightweight interactive topographic contours + cached accent shapes via Canvas 2D, Livewire: None, Alpine: dynamic-background --}}

{{-- Pattern Background: Interactive Topographic Lines & Chart --}}
<div id="dynamic-bg-container" class="pointer-events-none fixed inset-0 z-0 overflow-hidden"
    style="will-change: transform; transform: translate3d(0, 0, 0); backface-visibility: hidden;" x-data="dynamicBackground">
    {{-- Canvas layers (only Canvas 2D for static accent curves and topographic lines) --}}
    <div class="pointer-events-none absolute inset-0">
        <canvas x-ref="canvas2d" class="absolute inset-0 block h-full w-full"></canvas>
    </div>
</div>
