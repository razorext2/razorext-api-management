<div class="skeleton-loader mt-2 w-full animate-pulse space-y-2.5" role="status" {{ $attributes }}>
	<div class="flex w-full items-center">
		<div class="h-2.5 w-32 rounded-full bg-zinc-200 dark:bg-zinc-800/25"></div>
		<div class="ms-2 h-2.5 w-24 rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
		<div class="ms-2 h-2.5 w-full max-w-[400px] rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
	</div>
	<div class="flex w-full max-w-[480px] items-center">
		<div class="h-2.5 w-full rounded-full bg-zinc-200 dark:bg-zinc-800/25"></div>
		<div class="ms-2 h-2.5 w-full rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
		<div class="ms-2 h-2.5 w-24 rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
	</div>
	<span class="sr-only">Loading...</span>
</div>
