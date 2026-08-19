{{-- Goal: Offline notification alert widget, Livewire: -, Alpine: - --}}
@props(['class' => null, 'id' => null, 'title' => null, 'desc' => null])

<div x-bind:class="dynamicBg
    ?
    'border-red-500/10 bg-red-500/15 backdrop-blur-md dark:border-red-500/20 dark:bg-red-500/5 shadow-lg ' :
    'border-red-300 bg-red-100 dark:border-red-900 dark:bg-[#251010] shadow-sm'"
    {{ $attributes->merge(['class' => 'relative flex items-start gap-4 overflow-hidden rounded-xl border p-4 transition-all duration-300 md:p-6']) }}
    id="{{ $id }}" role="alert">

    {{-- Icon container --}}
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full p-2 transition-colors duration-300"
        x-bind:class="dynamicBg ? 'bg-red-500/25 dark:bg-red-500/15' : 'bg-red-200 dark:bg-red-900/30'">
        <x-icons.wifi-off class="h-6 w-6 text-red-600 dark:text-red-400" />
    </div>

    {{-- Content --}}
    <div class="flex flex-1 flex-col pt-0.5">
        @if ($title)
            <h3 class="text-sm font-bold text-red-900 dark:text-red-200">
                {{ $title }}
            </h3>
        @endif
        @if ($desc)
            <div class="mt-1 text-sm leading-relaxed text-red-800/90 dark:text-red-300">
                {{ $desc }}
            </div>
        @endif
        {{ $slot }}
    </div>
</div>

