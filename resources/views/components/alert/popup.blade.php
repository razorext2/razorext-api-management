<div class="fixed left-1/2 top-24 z-[70] flex w-full -translate-x-1/2 items-center px-4 transition-all duration-500 md:left-auto md:right-8 md:top-24 md:w-fit md:max-w-md md:translate-x-0 md:px-0"
    id="notification-container" role="alert" x-data="{ showToast: true }" x-init="setTimeout(() => showToast = false, 5000)" x-show="showToast"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="transform translate-y-[-20px] opacity-0 scale-95"
    x-transition:enter-end="transform translate-y-0 opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="transform translate-y-0 opacity-100 scale-100"
    x-transition:leave-end="transform translate-y-[-20px] opacity-0 scale-95">

    <div class="relative flex w-full items-center gap-4 overflow-hidden rounded-xl border border-white/40 p-5 shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:border-zinc-800 dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] md:gap-5"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

        <!-- Decoration Gradient -->
        <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl"></div>
        <div class="absolute -bottom-10 -right-10 h-24 w-24 rounded-full bg-indigo-500/10 blur-2xl"></div>

        <!-- Status Icon -->
        <div
            class="relative flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/20">
            <x-icons.question-circle class="h-5 w-5" />
        </div>

        <!-- Content -->
        <div class="flex-grow pr-6">
            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Pemberitahuan</h4>
            <div class="mt-1 text-sm font-medium leading-relaxed text-zinc-700 dark:text-zinc-200">
                {{ $slot }}
            </div>
        </div>

        <!-- Close Button -->
        <button @click="showToast = false"
            class="absolute right-3 top-3 flex h-7 w-7 items-center justify-center rounded-lg text-zinc-400 transition-all hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-500 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
            <span class="sr-only">Tutup</span>
            <x-icons.close class="h-4 w-4" />
        </button>

        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 h-0.5 w-full bg-zinc-100 dark:bg-zinc-800">
            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 shadow-[0_0_10px_rgba(59,130,246,0.5)]"
                x-init="$el.style.transition = 'width 5s linear';
                setTimeout(() => $el.style.width = '0%', 50)" style="width: 100%"></div>
        </div>
    </div>
</div>
