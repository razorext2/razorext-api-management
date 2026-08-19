{{-- Goal: Render date/name with optional inactive badge, Livewire: -, Alpine: - --}}
@props(['is_active' => null])

<div class="grid w-fit text-nowrap">
	<span class="font-medium inline-flex items-center gap-1.5">
		{{ $date }}
        <x-dashboard.badge-inactive :is_active="$is_active ?? true" />
	</span>
	<span class="text-xs text-zinc-400">
		{{ $name ?? '' }}
	</span>
</div>

