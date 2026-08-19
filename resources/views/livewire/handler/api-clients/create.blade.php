{{-- Goal: Create Form for API Client --}}
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Tambah API Client Baru</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftarkan aplikasi eksternal untuk mengakses API komputasi backend</p>
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
                <x-input.text id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Contoh: Aplikasi Web Apriori, POS Store Medan" required />
                <x-input.error :messages="$errors->get('name')" class="mt-1" />
            </div>

            {{-- Deskripsi --}}
            <div>
                <x-input.label for="description" value="Deskripsi / Keterangan" />
                <x-input.textarea id="description" class="mt-1 block w-full" wire:model="description" rows="2" placeholder="Keterangan penggunaan API..." />
                <x-input.error :messages="$errors->get('description')" class="mt-1" />
            </div>

            {{-- Generated API Key Box --}}
            <div class="rounded-xl border border-dashed border-red-300 bg-red-50/50 p-4 dark:border-red-900/50 dark:bg-red-950/20">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-400">Generated API Key & Secret Key</span>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Kunci ini akan digunakan oleh aplikasi client. <strong class="text-amber-600 dark:text-amber-400">Salin Secret Key sekarang — tidak akan ditampilkan lagi setelah disimpan.</strong></p>
                    </div>
                    <button type="button" wire:click="generateNewKey" class="text-xs font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                        🔄 Generate Ulang
                    </button>
                </div>
                <div class="mt-3 space-y-2">
                    <div>
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-zinc-400">API Key (X-API-KEY)</p>
                        <div class="flex items-center gap-2" x-data="{ copied: false }">
                            <input type="text" readonly wire:model="generated_key" value="{{ $generated_key }}" class="w-full rounded-lg border-zinc-300 bg-white font-mono text-sm text-zinc-800 select-all dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200" />
                            <button type="button"
                                @click="navigator.clipboard.writeText($wire.generated_key); copied = true; setTimeout(() => copied = false, 2000)"
                                class="rounded-lg border border-zinc-200 bg-white px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                <span x-show="!copied">📋 Salin</span>
                                <span x-show="copied" class="text-green-600 dark:text-green-400">✓</span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-zinc-400">Secret Key (untuk validasi signature, opsional)</p>
                        <div class="flex items-center gap-2" x-data="{ copied: false }">
                            <input type="text" readonly wire:model="generated_secret" value="{{ $generated_secret }}" class="w-full rounded-lg border-zinc-300 bg-white font-mono text-sm text-zinc-800 select-all dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200" />
                            <button type="button"
                                @click="navigator.clipboard.writeText($wire.generated_secret); copied = true; setTimeout(() => copied = false, 2000)"
                                class="rounded-lg border border-zinc-200 bg-white px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                <span x-show="!copied">📋 Salin</span>
                                <span x-show="copied" class="text-green-600 dark:text-green-400">✓</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Rate Limiting --}}
                <div>
                    <x-input.label for="rate_limit" value="Rate Limit (Request per Menit) *" />
                    <x-input.text id="rate_limit" type="number" min="1" max="10000" class="mt-1 block w-full" wire:model="rate_limit_per_minute" required />
                    <p class="mt-1 text-[11px] text-zinc-400">Maksimum request yang diizinkan per menit.</p>
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
                <x-input.text id="allowed_ips" type="text" class="mt-1 block w-full font-mono text-xs" wire:model="allowed_ips_text" placeholder="Contoh: 127.0.0.1, 192.168.1.100 (kosongkan jika terbuka untuk semua IP)" />
                <p class="mt-1 text-[11px] text-zinc-400">Pisahkan dengan koma jika lebih dari satu alamat IP.</p>
                <x-input.error :messages="$errors->get('allowed_ips_text')" class="mt-1" />
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <x-button.secondary href="{{ route('api-clients.index') }}" wire:navigate>
                    Batal
                </x-button.secondary>
                <x-button.primary type="submit">
                    Simpan & Buat API Client
                </x-button.primary>
            </div>
        </form>
    </div>
</div>
