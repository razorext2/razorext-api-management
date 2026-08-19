<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>401: Akses Tidak Diizinkan</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body x-data="{ dynamicBg: localStorage.getItem('dynamicBg') === null ? false : localStorage.getItem('dynamicBg') === 'true' }"
    class="bg-[#f8fafc] antialiased transition-colors duration-300 selection:bg-blue-100 selection:text-blue-700 dark:bg-[#0f172a] dark:selection:bg-blue-900 dark:selection:text-blue-200">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden p-4 sm:p-6 lg:p-8">
        {{-- Background Accents --}}
        <div class="pointer-events-none absolute h-full w-full opacity-30 dark:opacity-20">
            <div class="absolute -left-1/4 -top-1/4 h-1/2 w-1/2 rounded-full bg-blue-400 blur-[120px]"></div>
            <div class="absolute -bottom-1/4 -right-1/4 h-1/2 w-1/2 rounded-full bg-indigo-400 blur-[120px]"></div>
        </div>

        <div class="relative w-full max-w-6xl">
            <div class="grid items-center gap-8 rounded-[2.5rem] border border-white/40 p-8 shadow-2xl dark:border-zinc-800/50 lg:grid-cols-2 lg:p-16"
                x-bind:class="dynamicBg ?
                    'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                    'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

                {{-- Left Side: Content --}}
                <div class="order-2 space-y-8 text-center lg:order-1 lg:text-left">
                    <div class="space-y-4">
                        <h1
                            class="bg-linear-to-br from-blue-600 to-indigo-700 bg-clip-text text-8xl font-black leading-none text-transparent lg:text-9xl">
                            401
                        </h1>
                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white lg:text-5xl">
                            Akses Terbatas.
                        </h2>
                        <p class="mx-auto max-w-md text-lg leading-relaxed text-gray-600 dark:text-gray-400 lg:mx-0">
                            Maaf, Anda memerlukan akun yang sah untuk mengakses halaman ini atau sesi Anda telah
                            berakhir.
                        </p>
                    </div>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row lg:justify-start">
                        <x-button.link href="{{ route('login') }}"
                            class="flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-8 py-4 font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:scale-[1.02] hover:bg-blue-700 active:scale-[0.98]">
                            <span>Masuk Sekarang</span>
                        </x-button.link>

                        <x-button.secondary onclick="window.history.back()"
                            class="flex items-center justify-center gap-2 rounded-2xl border-white/20 px-8 py-4 font-bold shadow-sm transition-all hover:bg-white dark:border-zinc-700 dark:hover:bg-zinc-800"
                            x-bind:class="dynamicBg ?
                                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                            <span>Kembali</span>
                        </x-button.secondary>
                    </div>
                </div>

                {{-- Right Side: Illustration --}}
                <div class="order-1 flex items-center justify-center lg:order-2">
                    <img src="{{ asset('images/errors/403.png') }}" alt="401 Unauthorized"
                        class="animate-float w-full max-w-md drop-shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
