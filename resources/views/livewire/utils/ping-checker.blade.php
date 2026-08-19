<div x-data="{
    pingMs: null,
    isOnline: true,
    async checkPing() {
        const start = performance.now();
        try {
            const res = await fetch('{{ route('ping') }}', { method: 'GET', cache: 'no-store' });
            if (res.ok) {
                this.pingMs = Math.round(performance.now() - start);
                this.isOnline = true;
            } else {
                this.isOnline = false;
            }
        } catch (e) {
            this.isOnline = navigator.onLine;
            if (this.isOnline) {
                this.pingMs = Math.round(performance.now() - start);
            }
        }
    }
}" x-init="checkPing(); setInterval(() => checkPing(), 15000);"
    class="flex items-center gap-1.5 rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800/80 dark:text-zinc-300">
    
    <span class="relative flex h-2 w-2">
        <template x-if="isOnline">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
        </template>
        <span :class="isOnline ? 'bg-emerald-500' : 'bg-red-500'" class="relative inline-flex h-2 w-2 rounded-full"></span>
    </span>

    <span x-text="isOnline ? (pingMs !== null ? `${pingMs} ms` : 'Online') : 'Offline'"></span>
</div>
