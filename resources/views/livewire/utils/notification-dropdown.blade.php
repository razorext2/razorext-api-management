<div class="w-full">
    @forelse($this->notifications as $item)
        <div class="flex border-b hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800" wire:key="notif-{{ $item->id }}">
            <div class="w-full px-3.5 py-3 md:p-4">
                <div class="grid gap-1 text-sm text-zinc-500 dark:text-zinc-400">
                    <div class="grid grid-cols-2 text-xs font-medium text-zinc-700 dark:text-zinc-400">
                        <div class="text-left">
                            {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="font-base mb-1 text-zinc-800 dark:text-white">
                        {{ $item->data['message'] ?? 'Notifikasi baru' }}
                    </div>

                    <div class="inline-flex items-center mt-1">
                        @if(isset($item->data['button']) && isset($item->data['button']['url']))
                            <a href="{{ $item->data['button']['url'] }}" class="me-4 rounded-md bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                {{ $item->data['button']['label'] ?? 'Lihat Detail' }}
                            </a>
                        @endif
                        
                        <button class="text-xs font-semibold text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white transition-colors" wire:click="markAsRead('{{ $item->id }}')" type="button">
                            Tandai dibaca
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="w-full px-10 py-16 text-center text-sm text-zinc-500 dark:text-zinc-400">
            Tidak ada notifikasi baru.
        </div>
    @endforelse
</div>
