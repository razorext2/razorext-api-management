{{-- Goal: Session & Device Manager Component, Livewire: Yes, Alpine: Yes --}}
<div :class="dynamicBg
    ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm'
    : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
     class="rounded-xl border p-4 sm:p-6 transition-colors">

    <div class="mb-4 border-b border-zinc-200 pb-4 dark:border-zinc-800 space-y-3">
        <div>
            <h3 class="flex items-center gap-2 text-base font-semibold text-zinc-900 dark:text-white">
                <x-icons.computer class="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400" />
                <span>Manajemen Perangkat & Sesi Aktif</span>
            </h3>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                Kelola dan pantau semua sesi browser dan perangkat yang sedang terhubung ke akun Anda.
            </p>
        </div>

        @if (count($sessions) > 1)
            <div>
                <x-button.secondary wire:click="openConfirmLogoutModal" class="w-full sm:w-auto text-xs">
                    <x-slot name="icon">
                        <x-icons.arrow-left-bracket class="h-4 w-4" />
                    </x-slot>
                    Keluar dari Sesi Lainnya
                </x-button.secondary>
            </div>
        @endif
    </div>


    <div class="space-y-3">
        @foreach ($sessions as $session)
            <div class="flex items-center justify-between p-3.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-md bg-zinc-200/70 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                        @if ($session->agent['is_desktop'])
                            <x-icons.computer class="h-5 w-5" />
                        @else
                            <x-icons.phone class="h-5 w-5" />
                        @endif
                    </div>


                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $session->agent['platform'] }} — {{ $session->agent['browser'] }}
                            </span>

                            @if ($session->is_current_device)
                                <span class="px-2 py-0.5 text-[10px] font-semibold tracking-wide rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    Perangkat Ini
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                            IP: {{ $session->ip_address }} • Aktif: {{ $session->last_active }}
                        </p>
                    </div>
                </div>

                @if (! $session->is_current_device)
                    <x-button.danger wire:click="revokeSession('{{ $session->id }}')" class="text-xs">
                        <x-slot name="icon">
                            <x-icons.trash-bin class="h-4 w-4" />
                        </x-slot>
                        Cabut Sesi
                    </x-button.danger>
                @endif
            </div>
        @endforeach
    </div>


    {{-- Confirm Logout Other Sessions Modal --}}
    <x-modal.base-modal show="showConfirmLogoutModal" title="Keluarkan Sesi Lainnya" maxWidth="md">
        <x-slot name="icon">
            <x-icons.exclamation-triangle class="h-5 w-5 text-white" />
        </x-slot>

        <form wire:submit.prevent="logoutOtherSessions" id="formLogoutOtherSessions" class="space-y-4">
            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                Masukkan password Anda saat ini untuk mengonfirmasi pengeluaran sesi dari semua perangkat lain:
            </p>

            <div>
                <x-input.basic wire:model="password" type="password" label="Password Saat Ini" placeholder="••••••••" />
                <x-input.error :messages="$errors->get('password')" />
            </div>
        </form>

        <x-slot name="footer">
            <x-button.secondary @click="open = false">Batal</x-button.secondary>
            <x-button.danger type="submit" form="formLogoutOtherSessions">
                Keluarkan Semua Sesi Lain
            </x-button.danger>
        </x-slot>
    </x-modal.base-modal>
</div>


