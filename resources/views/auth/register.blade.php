{{-- Goal: Register page form, Livewire: None, Alpine: Yes (shares GuestLayout context) --}}
<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <div
            class="flex w-full flex-col rounded-2xl border-0 bg-transparent p-4 shadow-none dark:border-0 dark:bg-transparent dark:shadow-none sm:border sm:border-glass-border-light sm:bg-glass-light sm:p-10 sm: sm:shadow-glass-light sm: sm:dark:border-glass-border-dark sm:dark:bg-glass-dark sm:dark:shadow-glass-dark"
    x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-8 border-b border-glass-divider-light pb-5 dark:border-glass-divider-dark">
                <h2 class="text-left text-3xl font-black tracking-tight text-zinc-900 dark:text-white">
                    Registrasi
                </h2>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Daftar akun baru untuk mengakses sistem laporan.
                </p>
            </div>

            <form class="w-full" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-5 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="name"
                        :value="__('Name')" />
                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="name" name="name" type="text" :value="old('name')" required autofocus
                        autocomplete="name" placeholder="Nama Lengkap" />
                    <x-input.error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email Address -->
                <div class="mb-5 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="email"
                        :value="__('Email Account')" />
                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="email" name="email" type="email" :value="old('email')" required autocomplete="username"
                        placeholder="contoh@indodacin.com" />
                    <x-input.error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div class="mb-5 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="password"
                        :value="__('Password')" />
                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="password" name="password" type="password" required autocomplete="new-password"
                        placeholder="••••••••" />
                    <x-input.error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-8 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300"
                        for="password_confirmation" :value="__('Confirm Password')" />
                    <x-input.text
                        class="block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:bg-dark-secondary dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 dark:focus:bg-dark-secondary [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset] [&:-webkit-autofill]:[-webkit-text-fill-color:white]"
                        id="password_confirmation" name="password_confirmation" type="password" required
                        autocomplete="new-password" placeholder="••••••••" />
                    <x-input.error class="mt-2" :messages="$errors->get('password_confirmation')" />
                </div>

                <div class="flex w-full flex-col">
                    <button
                        class="flex w-full items-center justify-center rounded-xl bg-red-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-red-600/20 transition-all hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-dark-primary"
                        type="submit">
                        {{ __('Registrasi Akun') }}
                        <x-icons.arrow-right class="ml-2 h-4 w-4" />
                    </button>

                    <div class="mt-6 flex items-center justify-center">
                        @if (Route::has('login'))
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Sudah punya akun? </span>
                            <a class="ml-1 text-sm font-bold text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                href="{{ route('login') }}">
                                {{ __('Sign In') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
