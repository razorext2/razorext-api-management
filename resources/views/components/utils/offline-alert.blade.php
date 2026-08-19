{{-- Goal: Offline indicator alert component, Livewire: -, Alpine: Yes --}}
<div x-data="{ offline: !navigator.onLine }" {{ $attributes }} @offline.window="offline = true" @online.window="offline = false"
    x-show="offline" style="display: none;" x-transition>
    <x-alert.notification :id="'offline-alert'" type="offline">
        <div class="flex items-center gap-3">
            <x-icons.wifi-slash class="h-5 w-5 flex-shrink-0 text-red-500" />
            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Koneksi Internet Terputus</span>
        </div>
        <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">Beberapa fitur mungkin tidak dapat diakses sampai koneksi kembali stabil.</p>
    </x-alert.notification>
</div>
