{{-- Partial: Action buttons untuk PowerGrid table rows --}}
<div class="flex items-center justify-center gap-2">
    <x-button.primary href="{{ route($editRoute, $row->id) }}" wire:navigate class="text-xs py-1.5 px-3">
        Edit
    </x-button.primary>
</div>
