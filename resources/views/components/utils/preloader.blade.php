{{-- Goal: Diagonal split-screen preloader inspired by Danzan, Livewire: None, Alpine: None --}}
<div id="preloader" {{ $attributes }}>
    <!-- Left diagonal door -->
    <div class="door door-left"></div>

    <!-- Right diagonal door -->
    <div class="door door-right"></div>

    <!-- Sliced text layer (sits above doors) -->
    <div class="sliced-logo-wrapper">
        <div class="part-indo">INDO</div>
        <div class="part-line-wrapper">
            <div class="part-line"></div>
        </div>
        <div class="part-dacin">DACIN</div>
    </div>
</div>
