<div class="flex flex-wrap gap-1">
	@foreach ($items as $item)
		<span class="rounded-md bg-green-500 px-2 py-0.5 text-xs">
			{{ $item }}
		</span>
	@endforeach
</div>
