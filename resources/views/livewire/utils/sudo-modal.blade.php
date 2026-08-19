{{-- Goal: Sudo Mode Re-Authentication Modal, Livewire: Yes, Alpine: Yes --}}
<div>
    <x-modal.base-modal show="showModal" title="Verifikasi Sudo Mode (Aksi Sensitif)" maxWidth="md">
        <x-slot name="icon">
            <x-icons.exclamation-triangle class="h-5 w-5 text-white" />
        </x-slot>

        <div x-data="{
            loadingPasskey: false,
            passkeyError: '',
            async loginWithPasskey() {
                this.passkeyError = '';
                this.loadingPasskey = true;
                try {
                    const options = await this.$wire.getPasskeyOptions();
                    const credential = await window.WebAuthnHelper.authenticatePasskey(options);
                    await this.$wire.verifyPasskey(
                        credential.clientDataJSON,
                        credential.authenticatorData,
                        credential.signature,
                        credential.rawId
                    );
                } catch (err) {
                    this.passkeyError = err.message || 'Verifikasi Passkey gagal.';
                } finally {
                    this.loadingPasskey = false;
                }
            }
        }" class="space-y-4">

            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-xs text-amber-800 dark:text-amber-300">
                <p class="font-semibold flex items-center gap-1.5 mb-1">
                    <x-icons.lock class="h-4 w-4 inline" /> Verifikasi Keamanan Diperlukan
                </p>
                <p>Anda mencoba mejalankan <b>aksi penghapusan data</b>. Masukkan password atau pindai Passkey untuk mengonfirmasi identitas Anda.</p>
            </div>

            {{-- Password Form --}}
            <form wire:submit.prevent="verifyPassword" id="formVerifyPassword" class="space-y-4">
                <div>
                    <x-input.basic wire:model="password" type="password" label="Password Akun Anda" placeholder="••••••••" autofocus />
                    <x-input.error :messages="$errors->get('password')" />
                </div>

                <div x-show="passkeyError" x-cloak class="mt-2 text-xs text-red-600 dark:text-red-400">
                    <span x-text="passkeyError"></span>
                </div>
            </form>

            <x-slot name="footer">
                <div class="flex w-full items-center justify-between">
                    @if ($hasPasskeys)
                        <x-button.secondary type="button" @click="loginWithPasskey()" x-bind:disabled="loadingPasskey" class="text-xs">
                            <x-slot name="icon">
                                <x-icons.fingerprint class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </x-slot>
                            <span x-show="!loadingPasskey">Gunakan Passkey</span>
                            <span x-show="loadingPasskey">Scanning...</span>
                        </x-button.secondary>
                    @else

                        <div></div>
                    @endif

                    <div class="flex gap-2">
                        <x-button.secondary @click="open = false">Batal</x-button.secondary>
                        <x-button.primary type="submit" form="formVerifyPassword">
                            Konfirmasi Sudo
                        </x-button.primary>
                    </div>
                </div>
            </x-slot>
        </div>
    </x-modal.base-modal>
</div>


