{{-- Goal: Forgot password page form, Livewire: None, Alpine: Yes (shares GuestLayout context) --}}
<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <div class="sm:border-glass-border-light sm:bg-glass-light sm: sm:shadow-glass-light sm: sm:dark:border-glass-border-dark sm:dark:bg-glass-dark sm:dark:shadow-glass-dark flex w-full flex-col rounded-2xl border-0 bg-transparent p-4 shadow-none sm:border sm:p-10 dark:border-0 dark:bg-transparent dark:shadow-none"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-6 pb-2">
                <a href="{{ route('login') }}"
                    class="mb-6 inline-flex items-center text-sm font-semibold text-zinc-500 transition-colors hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white">
                    <x-icons.arrow-left class="mr-2 h-4 w-4" />
                    Kembali ke Login
                </a>
                <h2 class="mb-2 text-left text-3xl font-black tracking-tight text-zinc-900 dark:text-white">
                    Forgot Password
                </h2>
                <div class="mt-2 line-clamp-3 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Lupa password Anda? Tidak masalah. Beritahu kami alamat email akun Anda dan kami akan mengirimkan link reset.') }}
                </div>
            </div>

            <!-- Session Status -->
            <x-auth.auth-session-status class="mb-4" :status="session('status')" />

            <form class="w-full" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6 flex w-full flex-col">
                    <x-input.label class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300" for="email"
                        :value="__('Email Address')" />
                    <x-input.text
                        class="dark:bg-dark-secondary dark:focus:bg-dark-secondary block w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:border-red-500 focus:bg-white focus:ring-red-500 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500 dark:focus:border-red-500 [&:-webkit-autofill]:[-webkit-text-fill-color:white] [&:-webkit-autofill]:[box-shadow:0_0_0_1000px_#18181b_inset]"
                        id="email" name="email" type="email" :value="old('email')" required autofocus
                        placeholder="user@razorext.my.id" />
                    <x-input.error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="mt-8 flex w-full flex-col">
                    <button
                        class="dark:focus:ring-offset-dark-primary flex w-full items-center justify-center rounded-xl bg-red-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-red-600/20 transition-all hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        type="submit">
                        {{ __('Kirim Link Reset') }}
                        <x-icons.send-right class="ml-2 h-4 w-4" />
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
