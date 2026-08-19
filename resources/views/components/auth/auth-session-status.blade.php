@props(['status'])

@if ($status)
	<div {{ $attributes->merge(['class' => 'font-medium text-base dark:text-white text-zinc-800']) }}>
		{{ $status }}
	</div>
@endif
