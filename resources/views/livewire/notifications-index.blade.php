<div class="flex h-auto items-center justify-center">
    <div class="grid w-full gap-4 rounded-xl border border-zinc-200 p-4 shadow-md dark:border-zinc-800 dark:shadow-none md:gap-4 md:p-6"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

        <div id="notificationHeader"
            class="flex items-center justify-between border-b border-zinc-200 p-2 pb-4 dark:border-zinc-800 lg:pb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Semua Notifikasi</h2>

            <div class="max-w-xs">
                <x-button.success wire:click="markAllAsRead" type="button">
                    <x-slot name="icon">
                        <x-icons.checklist-stepper class="h-6 w-6" />
                    </x-slot>
                    {{ __('Mark All as Read') }}
                </x-button.success>
            </div>
        </div>

        <div class="grid gap-2 md:gap-4" id="notificationCenterContainer">
            @forelse($notifications as $notification)
                <div
                    class="{{ $notification->read_at == null ? 'bg-gray-100 dark:bg-gray-800' : '' }} flex rounded-lg transition-all duration-300 hover:scale-[1.01] hover:bg-gray-100 dark:hover:bg-gray-700">

                    <div class="w-full px-3.5 py-3 md:p-4">
                        <div class="grid gap-1 text-sm text-gray-500 dark:text-gray-400">
                            <div class="grid grid-cols-2 text-xs font-medium text-gray-700 dark:text-gray-400">
                                <div class="text-left">
                                    {{ $notification->data['created_at'] ?? $notification->created_at->diffForHumans() }}
                                </div>
                            </div>

                            {{-- show notification message --}}
                            <div
                                class="font-base {{ $notification->read_at == null ? 'font-semibold' : '' }} mb-1 text-gray-800 dark:text-white">
                                {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                            </div>

                            <div class="flex gap-2">
                                @if (isset($notification->data['button']))
                                    <a href="{{ $notification->data['button']['url'] ?? '#' }}"
                                        class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                                        {{ $notification->data['button']['label'] ?? 'Lihat Detail' }}
                                    </a>
                                @endif

                                {{-- mark as read --}}
                                @if ($notification->read_at == null)
                                    <x-button.success wire:click="markAsRead('{{ $notification->id }}')" type="button">
                                        {{ __('Mark as Read') }}
                                    </x-button.success>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            @empty
                <div class="w-full px-3.5 text-center text-sm text-gray-800 dark:text-white md:p-32"
                    id="notificationEmpty">
                    Tidak ada notifikasi.
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

