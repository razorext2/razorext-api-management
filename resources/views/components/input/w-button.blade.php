@props([
    'labels' => true,
    'id',
    'name',
    'textLabel' => null,
    'buttonLabel',
    'placeholder' => null,
    'value' => null,
    'icon' => true,
])

@if ($labels)
	<label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white" for="{{ $id }}">
		{{ $textLabel }}
	</label>
@endif

<div class="flex flex-row items-center gap-2">
	<div class="relative flex-1">
		@if ($icon)
			<div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
				<x-icons.search class="h-4 w-4 text-zinc-500 dark:text-zinc-400" />
			</div>
		@endif

		<x-input.basic class="{{ $icon ? 'ps-10' : '' }}" id="{{ $id }}" name="{{ $name }}"
			value="{{ $value }}" placeholder="{{ $placeholder }}" required :labels="false" />
	</div>

	<x-button.primary type="submit" class="shrink-0 focus:outline" id="{{ $id }}_submit">
		{{ $buttonLabel }}
	</x-button.primary>
</div>
