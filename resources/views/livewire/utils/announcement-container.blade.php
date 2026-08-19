{{-- Goal: Show announcement popup to user one by one, Livewire: Utils\AnnouncementContainer, Alpine: Scroll detection --}}
<div>
    @if ($announcement)
        <div x-data="{
            announcementId: @entangle('announcementId'),
            hasScrolledToEnd: false,
            checkScroll(e) {
                const el = e.target;
                if (el.scrollHeight <= el.clientHeight) {
                    this.hasScrolledToEnd = true;
                    return;
                }
                if (Math.ceil(el.scrollTop + el.clientHeight) >= el.scrollHeight - 10) {
                    this.hasScrolledToEnd = true;
                }
            },
            resetScroll() {
                this.hasScrolledToEnd = false;
                this.$nextTick(() => {
                    const anchor = document.querySelector('.announcement-scroll-anchor');
                    if (anchor) {
                        const el = anchor.closest('.overflow-y-auto');
                        if (el) {
                            el.scrollTop = 0;
                            if (el.scrollHeight <= el.clientHeight) {
                                this.hasScrolledToEnd = true;
                            }
                        }
                    }
                });
            }
        }"
        x-init="$watch('announcementId', value => {
            if (value) {
                resetScroll();
            }
        })">
            <x-modal.base-modal show="showModal" maxWidth="3xl" :title="$announcement->title" subtitle="PENGUMUMAN PENTING"
                :showCloseButton="false" :minimizeable="false">
                <x-slot name="icon">
                    <x-icons.bullhorn class="h-6 w-6" />
                </x-slot>

                <div x-ref="announcementContent" class="announcement-scroll-anchor" x-init="$nextTick(() => {
                    const el = $el.closest('.overflow-y-auto');
                    if (el) {
                        el.addEventListener('scroll', checkScroll.bind($data));
                        if (el.scrollHeight <= el.clientHeight) {
                            hasScrolledToEnd = true;
                        }
                    } else {
                        hasScrolledToEnd = true;
                    }
                });">

                    <div class="ql-snow text-gray-700 dark:text-gray-300">
                        <div class="ql-editor" style="padding: 0 !important; background-color: transparent !important;">
                            {!! $announcement->description !!}
                        </div>
                    </div>

                    @if ($announcement->file_path)
                        <div
                            class="mt-4 rounded-lg border border-zinc-200 bg-gray-50 p-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                            <p class="mb-2 text-sm font-medium">Lampiran File:</p>
                            <a href="{{ Storage::url($announcement->file_path) }}" target="_blank"
                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                <x-icons.file-invoice class="h-5 w-5" />
                                <span>Download Lampiran</span>
                            </a>
                        </div>
                    @endif
                </div>

                <x-slot name="footer">
                    <div class="flex w-full items-center justify-between">
                        <label class="flex items-center gap-3"
                            :class="hasScrolledToEnd ? 'cursor-pointer' : 'cursor-not-allowed opacity-50'">
                            <input type="checkbox" wire:model.live="hasRead" :disabled="!hasScrolledToEnd"
                                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50">
                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                :class="hasScrolledToEnd ? '' : 'opacity-50'">Saya telah membaca dan memahami pengumuman
                                ini.</span>
                        </label>

                        <x-button.primary wire:click="markAsRead" wire:loading.attr="disabled"
                            x-bind:disabled="!$wire.hasRead" type="button">
                            <span wire:loading.remove wire:target="markAsRead">Tutup</span>
                            <span wire:loading wire:target="markAsRead">Memproses...</span>
                        </x-button.primary>
                    </div>
                </x-slot>
            </x-modal.base-modal>
        </div>
    @endif
</div>
