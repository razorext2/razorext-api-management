{{-- Goal: Success alert toast message component, Livewire: None, Alpine: dynamicBg --}}
<div class="fixed bottom-28 z-[100] flex h-auto w-full scale-90 transform items-center divide-x rounded-xl opacity-0 transition duration-300 md:bottom-5"
    id="toast-bottom-right" role="alert">
    <div class="toast-bottom-right dark: mx-auto flex w-full max-w-xs items-center rounded-xl p-4 text-zinc-500 ring-1 ring-zinc-200 dark:text-zinc-100 dark:ring-zinc-800 md:fixed md:bottom-0 md:right-5 md:mx-0"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
        id="toast-success" role="alert">
        <div
            class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-500">
            <x-icons.check-circle class="h-5 w-5" />
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-normal text-black dark:text-white">{{ session('success') }}</div>
        <x-button.secondary
            class="!ms-auto !h-8 !w-8 !bg-transparent !p-1.5 text-zinc-400 !shadow-none !ring-0 hover:bg-zinc-100/50 hover:text-zinc-900 sm:-mx-1.5 sm:-my-1.5"
            data-dismiss-target="#toast-success" type="button" aria-label="Close" :iconOnly="true">
            <span class="sr-only">Close</span>
            <x-icons.close class="h-3 w-3" />
        </x-button.secondary>
    </div>
</div>
