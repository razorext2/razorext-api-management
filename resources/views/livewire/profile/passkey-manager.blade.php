{{-- Goal: Passkey & Biometric Manager Component, Livewire: Yes, Alpine: Yes --}}
<div :class="dynamicBg ?
    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
    class="rounded-xl border p-4 transition-colors sm:p-6">

    <div class="mb-4 space-y-3 border-b border-zinc-200 pb-4 dark:border-zinc-800">
        <div>
            <h3 class="flex items-center gap-2 text-base font-semibold text-zinc-900 dark:text-white">
                <x-icons.lock class="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400" />
                <span>Passkeys & Autentikasi Biometrik (Face ID / Touch ID)</span>
            </h3>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                Gunakan sidik jari, wajah, atau kunci keamanan hardware untuk login cepat tanpa password.
            </p>
        </div>

        <div>
            <x-button.primary wire:click="openRegisterModal" class="w-full sm:w-auto">
                <x-slot name="icon">
                    <x-icons.plus class="h-4 w-4" />
                </x-slot>
                {{ __('Tambah Passkey') }}
            </x-button.primary>
        </div>
    </div>

    {{-- Passkeys List --}}
    @if ($passkeys->count() > 0)
        <div class="space-y-3">
            @foreach ($passkeys as $passkey)
                <div
                    class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50/50 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-md bg-blue-100 p-2 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                            <x-icons.fingerprint class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $passkey->name }}
                            </p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Dibuat {{ $passkey->created_at->locale('id')->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <x-button.danger wire:click="deletePasskey({{ $passkey->id }})" iconOnly>
                        <x-slot name="icon">
                            <x-icons.trash-bin class="h-4 w-4" />
                        </x-slot>
                    </x-button.danger>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-6 text-center text-zinc-500 dark:text-zinc-400">
            <x-icons.lock class="mx-auto mb-2 h-8 w-8 opacity-50" />
            <p class="text-sm font-medium">Belum ada Passkey terdaftar</p>
            <p class="mt-0.5 text-xs">Daftarkan Passkey untuk login 1-klik menggunakan Face ID / Touch ID / Windows
                Hello.</p>
        </div>
    @endif

    {{-- Register Modal --}}
    <div x-data="{
        loading: false,
        errorMessage: '',
        async register() {
            this.errorMessage = '';
            if (!this.$wire.nickname || this.$wire.nickname.trim().length < 3) {
                this.errorMessage = 'Masukkan nama/nickname perangkat (min. 3 karakter).';
                return;
            }
            this.loading = true;
            try {
                const options = await this.$wire.getRegistrationOptions();
                const credential = await window.WebAuthnHelper.registerPasskey(options);
                await this.$wire.savePasskey(credential.clientDataJSON, credential.attestationObject);
            } catch (err) {
                this.errorMessage = err.message || 'Gagal membuat Passkey biometrik.';
            } finally {
                this.loading = false;
            }
        }
    }">
        <x-modal.base-modal show="showRegisterModal" title="Daftarkan Passkey Baru" maxWidth="md">
            <x-slot name="icon">
                <x-icons.fingerprint class="h-5 w-5 text-white" />
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Masukkan nama panggilan untuk perangkat ini (misalnya: <b>MacBook Touch ID</b> atau <b>HP Windows
                        Hello</b>):
                </p>

                <div>
                    <x-input.basic wire:model="nickname" label="Nama Perangkat / Passkey"
                        placeholder="Contoh: MacBook Air TouchID" />
                    <x-input.error :messages="$errors->get('nickname')" />
                </div>

                <div x-show="errorMessage" x-cloak
                    class="rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-600 dark:border-red-800 dark:bg-red-950/50 dark:text-red-400">
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <x-slot name="footer">
                <x-button.secondary @click="open = false">
                    {{ __('Batal') }}
                </x-button.secondary>
                <x-button.primary @click="register()" x-bind:disabled="loading">
                    <x-slot name="icon">
                        <x-icons.fingerprint class="h-4 w-4" />
                    </x-slot>
                    <span x-show="!loading">{{ __('Mulai Pindai Biometrik') }}</span>
                    <span x-show="loading">{{ __('Memproses...') }}</span>
                </x-button.primary>
            </x-slot>
        </x-modal.base-modal>
    </div>
</div>
