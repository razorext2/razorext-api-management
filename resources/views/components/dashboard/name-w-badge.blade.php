{{-- Goal: Render employee name with optional deactivation status badge, Livewire: -, Alpine: - --}}
@props(['name', 'is_active' => true])

<div class="flex items-center gap-x-2">
    <span>{{ $name }}</span>
    <x-dashboard.badge-inactive :is_active="$is_active" />
</div>
