{{-- Goal: Login page form, Livewire: None, Alpine: Yes (shares GuestLayout context) --}}
<x-guest-layout>
    <!-- Session Status -->
    @if (session('message'))
        <div class="alert alert-warning mb-4 rounded-xl">
            {{ session('message') }}
        </div>
    @endif

    <div class="mx-auto w-full max-w-md">
        <div
            class="flex w-full flex-col rounded-2xl p-4 sm:border sm:p-10 bg-transparent border-transparent shadow-none dark:bg-transparent dark:border-transparent dark:shadow-none"
            x-bind:class="dynamicBg ? 'sm:bg-glass-light sm:dark:bg-glass-dark sm:border-glass-border-light sm:dark:border-glass-border-dark sm:backdrop-blur-md sm:shadow-lg sm:shadow-red-500/10' : 'sm:bg-white sm:dark:bg-dark-primary sm:border-zinc-200 sm:dark:border-zinc-800 sm:shadow-sm'">
            <div class="mb-8 border-b border-glass-divider-light pb-5 dark:border-glass-divider-dark">
                <h2 class="text-left text-3xl font-black tracking-tight text-zinc-900 dark:text-white">
                    Sign In
                </h2>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Selamat datang kembali! Silakan masukkan kredensial Anda.
                </p>
            </div>

            <form class="w-full" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="email"
                        :value="__('Email Account')" />
                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="email" name="email" type="email" :value="old('email')" required autofocus
                        autocomplete="email" placeholder="contoh@indodacin.com" />
                    <x-input.error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="mb-6 flex w-full flex-col">
                    <div class="mb-2 flex items-center justify-between">
                        <x-input.label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="password"
                            :value="__('Password')" />
                    </div>

                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="password" name="password" type="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                    <x-input.error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <div class="mb-8 block">
                    <label class="inline-flex cursor-pointer items-center" for="remember_me">
                        <input
                            class="rounded border-zinc-300 text-red-600 shadow-sm focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:ring-offset-dark-primary"
                            id="remember_me" name="remember" type="checkbox">
                        <span
                            class="ms-2 select-none text-sm text-zinc-600 dark:text-zinc-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex w-full flex-col gap-3">
                    <button
                        class="flex w-full items-center justify-center rounded-xl bg-red-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-red-600/20 transition-all hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-dark-primary"
                        type="submit">
                        {{ __('Sign In') }}
                        <x-icons.arrow-right class="ml-2 h-4 w-4" />
                    </button>

                    <div x-data="{
                        loading: false,
                        errorMsg: '',
                        async loginPasskey() {
                            this.errorMsg = '';
                            this.loading = true;
                            try {
                                const optResp = await fetch('{{ route('webauthn.login.options') }}');
                                const optData = await optResp.json();
                                if (!optData.success) throw new Error(optData.message);

                                const credential = await window.WebAuthnHelper.authenticatePasskey(optData.options);

                                const loginResp = await fetch('{{ route('webauthn.login') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        id: credential.rawId,
                                        clientDataJSON: credential.clientDataJSON,
                                        authenticatorData: credential.authenticatorData,
                                        signature: credential.signature
                                    })
                                });
                                const loginData = await loginResp.json();
                                if (!loginData.success) throw new Error(loginData.message);

                                window.location.href = loginData.redirect;
                            } catch (err) {
                                this.errorMsg = err.message || 'Login dengan Passkey gagal.';
                            } finally {
                                this.loading = false;
                            }
                        }
                    }" class="w-full">
                        <button type="button" @click="loginPasskey()" x-bind:disabled="loading"
                            class="flex w-full items-center justify-center rounded-xl border border-zinc-200 bg-zinc-50 py-3 text-sm font-semibold text-zinc-800 shadow-xs transition-all hover:bg-zinc-100 dark:border-zinc-700 dark:bg-dark-secondary dark:text-zinc-200 dark:hover:bg-zinc-800">
                            <x-icons.fingerprint class="mr-2 h-4 w-4 text-blue-600 dark:text-blue-400" />
                            <span x-show="!loading">Masuk dengan Passkey / Face ID</span>
                            <span x-show="loading">Scanning Biometrik...</span>
                        </button>
                        <p x-show="errorMsg" x-cloak class="mt-2 text-center text-xs text-red-600 dark:text-red-400" x-text="errorMsg"></p>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
