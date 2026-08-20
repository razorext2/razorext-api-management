{{-- Goal: Authentication page layout, Livewire: None, Alpine: Yes --}}
<!DOCTYPE html>
<html class="{{ isset($_COOKIE['color-theme']) && $_COOKIE['color-theme'] === 'dark' ? 'dark' : '' }}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    @livewireStyles()

    <!-- Fonts -->
    <link href="{{ asset('images/brand/logo.ico') }}" rel="icon" />
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body id="container" class="relative min-h-screen overflow-x-hidden bg-zinc-50 antialiased dark:bg-zinc-950"
    x-data="{ dynamicBg: localStorage.getItem('dynamicBg') === null ? false : localStorage.getItem('dynamicBg') === 'true' }"
    onmousemove="document.getElementById('container').style.setProperty('--mouse-x', event.clientX + 'px'); document.getElementById('container').style.setProperty('--mouse-y', event.clientY + 'px');">

    <div x-show="dynamicBg" x-transition.opacity.duration.500ms>
        <x-utils.dynamic-background />
    </div>

    @if (session('status'))
        <div class="fixed bottom-5 right-5 z-50 flex w-full max-w-xs scale-90 transform items-center divide-x rounded-lg transition duration-300"
            id="toast-bottom-right" role="alert" x-data="{ showToast: true }" x-init="setTimeout(() => showToast = false, 3000)" x-show="showToast"
            x-transition:enter="transition ease-in duration-300" x-transition:enter-start="transform scale-90 opacity-0"
            x-transition:enter-end="transform scale-100 opacity-100"
            x-transition:leave="transition ease-out duration-300"
            x-transition:leave-start="transform scale-100 opacity-100"
            x-transition:leave-end="transform scale-90 opacity-0">
            <div class="mb-4 flex w-full max-w-xs items-center rounded-lg bg-white p-4 text-gray-500 shadow ring-1 ring-zinc-200 dark:bg-dark-primary dark:text-white dark:ring-zinc-800"
                id="toast-success" role="alert">
                <div
                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-500 dark:text-white">
                    <x-icons.check-circle class="h-5 w-5" />
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 mt-0.5 text-sm font-normal text-black"><x-auth.auth-session-status class="mb-4"
                        :status="session('status')" />
                </div>
                <x-button.danger
                    class="ms-auto! h-8! w-8! bg-transparent! p-1.5! shadow-none! ring-0 sm:-mx-1.5 sm:-my-1.5"
                    type="button" aria-label="Close" @click="showToast = false">
                    <span class="sr-only">Close</span>
                    <x-icons.close class="h-3 w-3" />
                </x-button.danger>
            </div>
        </div>
    @endif

    {{-- Fixed Logo Top-Left --}}
    <div class="fixed left-6 top-5 z-50 hidden md:block">
        <img src="{{ asset('images/brand/logo.png') }}" class="h-10 w-auto object-contain drop-shadow-sm" alt="Logo">
    </div>

    {{-- Theme Toggle Top-Right (Collapsible) --}}
    <div x-data="{ showSettings: false }" class="fixed right-6 top-5 z-50 flex items-center justify-end">

        {{-- Settings Menu (Expands to Left) --}}
        <div class="flex origin-right items-center gap-1 overflow-hidden transition-all duration-300 ease-in-out"
            x-bind:class="{
                'max-w-75 opacity-100 mr-2 rounded-2xl border p-1.5': showSettings,
                'max-w-0 opacity-0 mr-0 border-0 p-0': !showSettings,
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm': dynamicBg,
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm': !dynamicBg
            }">
            <div
                class="flex items-center gap-2 whitespace-nowrap border-r border-zinc-200 pl-2 pr-2 dark:border-zinc-800">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Dynamic
                    Bg</span>
                <button type="button" @click="dynamicBg = !dynamicBg; localStorage.setItem('dynamicBg', dynamicBg)"
                    class="relative inline-flex h-4 w-7 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:focus:ring-offset-dark-primary"
                    role="switch" :aria-checked="dynamicBg.toString()">
                    <span class="sr-only">Toggle dynamic background</span>
                    <span aria-hidden="true" x-bind:class="dynamicBg ? 'bg-red-500' : 'bg-zinc-300 dark:bg-zinc-700'"
                        class="pointer-events-none absolute mx-auto h-3 w-6 rounded-full transition-colors duration-200 ease-in-out"></span>
                    <span aria-hidden="true"
                        x-bind:class="dynamicBg ? 'translate-x-[0.35rem]' : 'translate-x-[-0.35rem]'"
                        class="pointer-events-none absolute left-1/2 -ml-1.5 inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out"></span>
                </button>
            </div>
            <div class="flex shrink-0">
                <x-button.light />
                <x-button.dark />
            </div>
        </div>

        {{-- Vertical Three-Dots Button --}}
        <button @click="showSettings = !showSettings" type="button"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border transition-all"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <x-icons.three-dots-vertical class="h-5 w-5 text-zinc-600 dark:text-zinc-400" />
        </button>
    </div>

    <div class="container mx-auto flex min-h-screen items-center justify-center px-6 py-24 md:py-0">
        <div class="flex w-full max-w-6xl flex-col justify-between gap-10 md:flex-row md:items-center">
            {{-- Branding Area --}}
            <div
                class="relative z-20 flex w-full flex-col items-center text-center md:w-1/2 md:items-start md:text-left">

                <h1
                    class="flex flex-col items-center text-center text-4xl font-black leading-tight tracking-tight text-zinc-900 drop-shadow-sm dark:text-white md:items-start md:text-left md:text-5xl lg:text-[3.5rem]">
                    <span>{{ setting('site_name', 'RazorAPI') }}</span>
                    <span x-data="{
                        words: [{{ json_encode(setting('auth_subtitle', 'API Platform')) }}, 'Fast & Secure', 'Scalable Gateway', 'Developer First'],
                        currentWord: '',
                        wordIndex: 0,
                        charIndex: 0,
                        isDeleting: false,
                        type() {
                            const current = this.words[this.wordIndex];
                    
                            if (this.isDeleting) {
                                this.currentWord = current.substring(0, this.charIndex - 1);
                                this.charIndex--;
                            } else {
                                this.currentWord = current.substring(0, this.charIndex + 1);
                                this.charIndex++;
                            }
                    
                            let typeSpeed = 100 - Math.random() * 50;
                            if (this.isDeleting) typeSpeed /= 2.5; // Delete faster
                    
                            if (!this.isDeleting && this.currentWord === current) {
                                typeSpeed = 2000; // Pause at the end before deleting
                                this.isDeleting = true;
                            } else if (this.isDeleting && this.currentWord === '') {
                                this.isDeleting = false;
                                this.wordIndex = (this.wordIndex + 1) % this.words.length;
                                typeSpeed = 500; // Pause before starting new word
                            }
                    
                            setTimeout(() => this.type(), typeSpeed);
                        }
                    }" x-init="setTimeout(() => type(), 800)"
                        class="bg-linear-to-r from-red-400 to-red-700 bg-clip-text text-transparent after:animate-pulse after:content-['|'] dark:from-rose-300 dark:to-red-500">
                        <span x-text="currentWord">{{ setting('auth_subtitle', 'API Platform') }}</span>
                    </span>
                </h1>
                <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-zinc-600 dark:text-zinc-300 md:mx-0">
                    {{ setting('auth_description', 'Platform manajemen API modern untuk pengelolaan gateway, autentikasi client, dan analitik performa.') }}
                </p>
            </div>

            {{-- Form Area Slot --}}
            <div class="relative z-10 flex w-full justify-center pb-10 md:w-1/2 md:justify-end md:pb-0">
                {{ $slot }}
            </div>

        </div>
    </div>

    @livewireScripts()

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const darkBtn = document.getElementById("theme-toggle-dark");
            const lightBtn = document.getElementById("theme-toggle-light");

            function toggleTheme(isDark) {
                document.documentElement.classList.toggle("dark", isDark);
                localStorage.setItem("color-theme", isDark ? "dark" : "light");
                document.cookie = "color-theme=" + (isDark ? "dark" : "light") +
                    "; path=/; max-age=31536000; SameSite=Lax";

                if (darkBtn) {
                    darkBtn.classList.toggle("text-gray-300", isDark);
                    darkBtn.classList.toggle("text-gray-200", !isDark);
                }
                if (lightBtn) {
                    lightBtn.classList.toggle("text-gray-700", isDark);
                    lightBtn.classList.toggle("text-red-400", !isDark);
                }
            }

            if (darkBtn) darkBtn.addEventListener("click", () => toggleTheme(true));
            if (lightBtn) lightBtn.addEventListener("click", () => toggleTheme(false));

            // Set initial state for button colors based on current theme
            const isDarkMode = document.documentElement.classList.contains('dark');
            toggleTheme(isDarkMode);
        });
    </script>
</body>

</html>
