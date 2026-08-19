{{-- Goal: Reusable banner to remind user to set up their digital signature, Livewire: -, Alpine: - --}}
@if (method_exists(auth()->user(), 'hasBeenSigned') && !auth()->user()->hasBeenSigned())
    <div x-bind:class="dynamicBg
        ?
        'border-amber-500/20 bg-amber-500/15 backdrop-blur-md dark:border-amber-500/20 dark:bg-amber-500/5 shadow-lg shadow-amber-500/10' :
        'border-amber-300 bg-amber-100 dark:border-amber-900 dark:bg-[#252210] shadow-sm'"
        {{ $attributes->merge(['class' => 'flex border flex-col gap-4 rounded-xl p-4 shadow-sm md:p-6 transform-gpu']) }}>
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div class="rounded-full bg-amber-100 p-2 dark:bg-amber-900/30">
                    <x-icons.file-pen class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div class="flex flex-col">
                    <h3 class="text-sm font-bold text-amber-900 dark:text-amber-300">Tanda Tangan Belum Diatur</h3>
                    <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">
                        Kamu belum mengatur tanda tangan digital. Tanda tangan digital diperlukan untuk menyetujui
                        pengajuan cuti, SPK, dan dokumen penting lainnya di sistem.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 self-end md:self-auto">
                <x-button.warning href="{{ route('profile.edit') }}" wire:navigate>
                    <x-slot name="icon">
                        <x-icons.pen-nib class="h-4 w-4 text-white" />
                    </x-slot>
                    Buat Tanda Tangan
                </x-button.warning>
            </div>
        </div>
    </div>
@endif
