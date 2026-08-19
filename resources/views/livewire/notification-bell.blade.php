<div class="relative w-full text-gray-500 dark:text-white" id="notifications-bell" wire:poll.300s>
	<x-icons.bell class="h-7 w-7" />

	@if ($notification->count() > 0)
		<div class="absolute -top-0.5 right-4 block" id="notificationDot" aria-live="polite">
			<span class="absolute mx-auto inline-flex h-full w-full animate-ping rounded-full bg-yellow-400 opacity-75"></span>
			<span
				class="absolute min-h-4 min-w-4 rounded-full bg-red-500 px-0.5 text-xs text-white">{{ $notification->count() >= 99 ? '99+' : $notification->count() }}</span>
		</div>
	@endif

</div>
