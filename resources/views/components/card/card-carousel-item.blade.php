{{-- Goal: Optimised stat card — reduced blur layers for smooth sidebar/page transitions, Livewire: components.card, Alpine: Yes (inherits dynamicBg) --}}
@php
    $colorMap = [
        'blue' => [
            'accent' => 'from-blue-400 via-sky-400 to-blue-600',
            'glow' => 'bg-blue-500/15 dark:bg-blue-500/10',
            'icon-bg' => 'bg-blue-500/10 dark:bg-blue-500/15',
            'icon-ring' => 'ring-blue-400/30 dark:ring-blue-500/20',
            'icon-text' => 'text-blue-600 dark:text-blue-300',
            'progress' => 'from-blue-400 to-sky-500',
            'progress-glow' => 'rgba(56,189,248,0.5)',
            'value-from' => 'from-blue-600',
            'value-to' => 'to-sky-500',
            'value-dark-from' => 'dark:from-blue-300',
            'value-dark-to' => 'dark:to-sky-200',
            'label-hover' => 'group-hover:text-blue-600 dark:group-hover:text-blue-400',
            'shadow-hover' => 'group-hover:shadow-blue-500/15 dark:group-hover:shadow-blue-500/10',
        ],
        'red' => [
            'accent' => 'from-red-400 via-rose-400 to-red-600',
            'glow' => 'bg-red-500/15 dark:bg-red-500/10',
            'icon-bg' => 'bg-red-500/10 dark:bg-red-500/15',
            'icon-ring' => 'ring-red-400/30 dark:ring-red-500/20',
            'icon-text' => 'text-red-600 dark:text-red-300',
            'progress' => 'from-red-400 to-rose-500',
            'progress-glow' => 'rgba(251,113,133,0.5)',
            'value-from' => 'from-red-600',
            'value-to' => 'to-rose-500',
            'value-dark-from' => 'dark:from-red-300',
            'value-dark-to' => 'dark:to-rose-200',
            'label-hover' => 'group-hover:text-red-600 dark:group-hover:text-red-400',
            'shadow-hover' => 'group-hover:shadow-red-500/15 dark:group-hover:shadow-red-500/10',
        ],
        'yellow' => [
            'accent' => 'from-yellow-400 via-amber-400 to-yellow-600',
            'glow' => 'bg-yellow-500/15 dark:bg-yellow-500/10',
            'icon-bg' => 'bg-yellow-500/10 dark:bg-yellow-500/15',
            'icon-ring' => 'ring-yellow-400/30 dark:ring-yellow-500/20',
            'icon-text' => 'text-yellow-600 dark:text-yellow-300',
            'progress' => 'from-yellow-400 to-amber-500',
            'progress-glow' => 'rgba(251,191,36,0.5)',
            'value-from' => 'from-yellow-600',
            'value-to' => 'to-amber-500',
            'value-dark-from' => 'dark:from-yellow-300',
            'value-dark-to' => 'dark:to-amber-200',
            'label-hover' => 'group-hover:text-yellow-600 dark:group-hover:text-yellow-400',
            'shadow-hover' => 'group-hover:shadow-yellow-500/15 dark:group-hover:shadow-yellow-500/10',
        ],
        'green' => [
            'accent' => 'from-green-400 via-emerald-400 to-green-600',
            'glow' => 'bg-green-500/15 dark:bg-green-500/10',
            'icon-bg' => 'bg-green-500/10 dark:bg-green-500/15',
            'icon-ring' => 'ring-green-400/30 dark:ring-green-500/20',
            'icon-text' => 'text-green-600 dark:text-green-300',
            'progress' => 'from-green-400 to-emerald-500',
            'progress-glow' => 'rgba(52,211,153,0.5)',
            'value-from' => 'from-green-600',
            'value-to' => 'to-emerald-500',
            'value-dark-from' => 'dark:from-green-300',
            'value-dark-to' => 'dark:to-emerald-200',
            'label-hover' => 'group-hover:text-green-600 dark:group-hover:text-green-400',
            'shadow-hover' => 'group-hover:shadow-green-500/15 dark:group-hover:shadow-green-500/10',
        ],
    ];

    $style = $colorMap[$color] ?? $colorMap['red'];
    $visibleCount = $visibleCount ?? 1;
    $itemClasses = 'min-w-[260px] shrink-0 xl:flex-1 xl:min-w-0';
@endphp

<div class="{{ $itemClasses }} group relative snap-start will-change-transform">

    {{-- Lift wrapper: GPU-only transform, no layout impact --}}
    <div class="relative h-full transition-transform duration-300 ease-out group-hover:-translate-y-1">

        {{-- Main card: bg & border transition only (cheap), blur only when dynamicBg is on --}}
        <div class="{{ $style['shadow-hover'] }} relative h-full overflow-hidden rounded-2xl border p-5 shadow-sm transition-[box-shadow,border-color,background-color] duration-300"
            :class="dynamicBg
                ?
                'bg-white/55 border-white/40 backdrop-blur-sm dark:bg-zinc-900/55 dark:border-zinc-700/50' :
                'bg-white border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800'">

            {{-- Coloured top accent bar (static, no hover animation) --}}
            <div class="{{ $style['accent'] }} absolute inset-x-0 top-0 h-0.75 bg-linear-to-r opacity-90"></div>

            {{-- Single decorative glow orb — static, blur-lg (was blur-2xl/3xl), no hover scale --}}
            <div
                class="{{ $style['glow'] }} pointer-events-none absolute -right-5 -top-5 h-24 w-24 rounded-full opacity-70 blur-lg">
            </div>

            {{-- Inner glass sheen (no blur, just gradient) --}}
            <div
                class="from-white/8 dark:from-white/4 pointer-events-none absolute inset-0 rounded-2xl bg-linear-to-br via-transparent to-transparent">
            </div>

            <div class="relative z-10">

                {{-- Header row --}}
                <div class="mb-4 flex items-start justify-between gap-2">
                    <p
                        class="{{ $style['label-hover'] }} text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 transition-colors duration-200 dark:text-zinc-500">
                        {{ $label }}
                    </p>

                    {{-- Icon badge: no backdrop-blur, no rotate (scale only) --}}
                    <div
                        class="{{ $style['icon-bg'] }} {{ $style['icon-ring'] }} {{ $style['icon-text'] }} flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ring-1 transition-transform duration-300 ease-out group-hover:scale-110">
                        <x-dynamic-component :component="$icon" class="h-4 w-4" />
                    </div>
                </div>

                {{-- Count value (no hover scale — text scale is expensive) --}}
                <div class="flex items-end gap-2">
                    <span
                        class="{{ $style['value-from'] }} {{ $style['value-to'] }} {{ $style['value-dark-from'] }} {{ $style['value-dark-to'] }} inline-block bg-linear-to-br bg-clip-text text-4xl font-black tracking-tight text-transparent">
                        {{ number_format($count) }}
                    </span>
                    <span class="mb-1.5 text-[11px] font-semibold text-zinc-400 dark:text-zinc-500">
                        {{ $indicator }}
                    </span>
                </div>

                {{-- Progress track (static width, no hover expand — was triggering layout recalc) --}}
                <div class="mt-5 h-1 w-full overflow-hidden rounded-full bg-zinc-200/60 dark:bg-zinc-700/50">
                    <div class="{{ $style['progress'] }} h-full w-1/2 rounded-full bg-linear-to-r"
                        style="box-shadow: 0 0 6px {{ $style['progress-glow'] }};"></div>
                </div>

            </div>
        </div>
    </div>
</div>
