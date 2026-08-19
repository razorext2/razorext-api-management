{{-- Goal: Edit Form for API Client --}}
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Edit API Client: {{ $client->name }}</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola pengaturan akses dan konfigurasi API key</p>
        </div>
        <x-button.secondary href="{{ route('api-clients.index') }}" wire:navigate>
            Kembali
        </x-button.secondary>
    </div>

    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
        <form wire:submit="save" class="space-y-5">
            {{-- Nama Aplikasi --}}
            <div>
                <x-input.label for="name" value="Nama Aplikasi / Client *" />
                <x-input.text id="name" type="text" class="mt-1 block w-full" wire:model="name" required />
                <x-input.error :messages="$errors->get('name')" class="mt-1" />
            </div>

            {{-- Deskripsi --}}
            <div>
                <x-input.label for="description" value="Deskripsi / Keterangan" />
                <x-input.textarea id="description" class="mt-1 block w-full" wire:model="description" rows="2" />
                <x-input.error :messages="$errors->get('description')" class="mt-1" />
            </div>

            {{-- API Key Box --}}
            <div class="rounded-xl border border-dashed border-red-300 bg-red-50/50 p-4 dark:border-red-900/50 dark:bg-red-950/20">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-400">Active API Key (X-API-KEY)</span>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Kunci akses aktif untuk request HTTP.</p>
                    </div>
                    <button type="button" wire:click="regenerateKey" wire:confirm="Apakah Anda yakin ingin me-regenerate API key? Client aplikasi harus diperbarui dengan key baru!" class="text-xs font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                        🔄 Regenerate Key Baru
                    </button>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    <input type="text" readonly value="{{ $api_key }}" class="w-full rounded-lg border-zinc-300 bg-white font-mono text-sm text-zinc-800 select-all dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200" />
                </div>
                @if ($key_changed)
                    <div class="mt-2 flex items-center gap-1.5 rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 dark:border-amber-700/50 dark:bg-amber-950/30">
                        <span class="text-amber-600 dark:text-amber-400">⚠️</span>
                        <p class="text-[11px] font-semibold text-amber-700 dark:text-amber-400">Key baru ini <strong>belum tersimpan</strong>. Klik "Simpan Perubahan" untuk mengaktifkan key baru ini.</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Rate Limiting --}}
                <div>
                    <x-input.label for="rate_limit" value="Rate Limit (Request per Menit) *" />
                    <x-input.text id="rate_limit" type="number" min="1" max="10000" class="mt-1 block w-full" wire:model="rate_limit_per_minute" required />
                    <x-input.error :messages="$errors->get('rate_limit_per_minute')" class="mt-1" />
                </div>

                {{-- Status Aktif --}}
                <div>
                    <x-input.label for="status" value="Status Akses *" />
                    <select id="status" wire:model="is_active" class="mt-1 block w-full rounded-xl border-zinc-200 bg-white py-2.5 pl-3 pr-8 text-sm text-zinc-900 focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/20 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white">
                        <option value="1">Aktif (Izinkan Request)</option>
                        <option value="0">Nonaktif (Blokir Request)</option>
                    </select>
                </div>
            </div>

            {{-- IP Whitelist --}}
            <div>
                <x-input.label for="allowed_ips" value="IP Whitelist (Opsional)" />
                <x-input.text id="allowed_ips" type="text" class="mt-1 block w-full font-mono text-xs" wire:model="allowed_ips_text" placeholder="Contoh: 127.0.0.1, 192.168.1.100" />
                <p class="mt-1 text-[11px] text-zinc-400">Pisahkan dengan koma jika lebih dari satu alamat IP.</p>
                <x-input.error :messages="$errors->get('allowed_ips_text')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between border-t border-zinc-200/60 pt-4 dark:border-zinc-800">
                <button type="button" wire:click="deleteClient" wire:confirm="Hapus API Client ini secara permanen?" class="text-xs font-bold text-red-600 hover:text-red-700 dark:text-red-400">
                    🗑️ Hapus Client Ini
                </button>

                <div class="flex gap-3">
                    <x-button.secondary href="{{ route('api-clients.index') }}" wire:navigate>
                        Batal
                    </x-button.secondary>
                    <x-button.primary type="submit">
                        Simpan Perubahan
                    </x-button.primary>
                </div>
            </div>
        </form>
    </div>
</div>
