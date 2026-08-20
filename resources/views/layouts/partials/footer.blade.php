<footer
    x-bind:class="dynamicBg
        ?
        'border-t border-glass-border-light bg-white/95 dark:border-glass-border-dark dark:bg-zinc-900/95' :
        'border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-dark-primary'"
    class="absolute bottom-0 w-full rounded-b-2xl px-4 py-3">
    <div class="flex flex-col gap-0.5">
        <div class="flex items-center gap-1.5">
            <span
                class="inline-flex h-1.5 w-1.5 shrink-0 rounded-full bg-red-600 shadow-[0_0_5px_rgba(220,38,38,0.5)]"></span>
            <a class="text-xs font-semibold text-red-600 transition-colors hover:text-red-700 dark:text-red-500 dark:hover:text-red-400"
                href="{{ setting('footer_url', 'https://razorext.my.id') }}">{{ setting('footer_company', 'RazorAPI™') }}</a>
        </div>
        <p class="text-[10px] leading-tight text-zinc-400 dark:text-zinc-600">
            © {{ date('Y') }} — {{ setting('footer_copyright', 'All Rights Reserved.') }}
        </p>
    </div>
</footer>
