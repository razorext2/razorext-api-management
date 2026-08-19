{{-- Goal: Render dynamic breadcrumb navigation with truncated segment titles, Livewire: Utils\Breadcrumb, Alpine: Scroll detector and smooth navbar cross-fade --}}
@php
    $total = count($crumbs);
    $displayCrumbs = [];
    if ($total >= 4) {
        $displayCrumbs[] = $crumbs[0];
        $displayCrumbs[] = $crumbs[1];
        $displayCrumbs[] = ['is_ellipsis' => true];
        $displayCrumbs[] = $crumbs[$total - 1];
    } else {
        $displayCrumbs = $crumbs;
    }
@endphp

<div wire:ignore x-data="{
    isSticky: false,
    init() {
        const checkScroll = () => {
            this.isSticky = window.scrollY > 30;
        };
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    }
}">
    {{-- Normal State: rendered in the page flow under the title, minimalist borderless style. Parent wrapper in title.blade.php handles height transitions. --}}
    <nav class="flex justify-start" aria-label="Breadcrumb">
        <ol class="inline-flex items-center gap-1 bg-transparent border-none p-0 shadow-none md:gap-1.5">
            @foreach ($displayCrumbs as $i => $crumb)
                <li class="flex items-center">
                    <div class="flex items-center">
                        @if ($i > 0)
                            <x-icons.angle-right class="mx-1.5 h-3 w-3 text-zinc-400 dark:text-zinc-500" />
                        @endif

                        @if (isset($crumb['is_ellipsis']))
                            <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 dark:text-zinc-500 leading-none">
                                ...
                            </span>
                        @else
                            @php
                                $isLast = $loop->last;
                                $isFirst = $i === 0;
                                $crumbTitle = count($crumbs) > 2 && $isFirst ? '' : $crumb['title'];
                            @endphp

                            @if (!$isLast && !empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}"
                                    class="group flex items-center leading-none whitespace-nowrap text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-500 transition-all hover:text-red-600 dark:text-zinc-400 dark:hover:text-red-500">
                                    @if ($isFirst)
                                        <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5 transition-transform group-hover:scale-110" />
                                    @endif
                                    <span class="leading-none inline-block max-w-17.5 sm:max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                </a>
                            @elseif (!$isLast)
                                <span
                                    class="flex items-center leading-none whitespace-nowrap text-[10px] font-bold uppercase tracking-[0.15em] text-zinc-400 dark:text-zinc-500">
                                    @if ($isFirst)
                                        <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5" />
                                    @endif
                                    <span class="leading-none inline-block max-w-17.5 sm:max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                </span>
                            @else
                                <span
                                    class="flex items-center leading-none whitespace-nowrap text-[10px] font-black uppercase tracking-[0.15em] text-red-600 dark:text-red-500">
                                    @if ($isFirst)
                                        <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5" />
                                    @endif
                                    <span class="leading-none inline-block max-w-17.5 sm:max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                </span>
                            @endif
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>

    {{-- Sticky State: teleported into the navbar --}}
    <template x-teleport="#navbar-breadcrumb-container">
        <nav x-show="isSticky"
            x-transition:enter="transition ease-out duration-250 transform"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex min-w-0 w-full" aria-label="Breadcrumb" style="display: none;">
            <ol class="inline-flex items-center gap-1 bg-transparent border-none p-0 shadow-none md:gap-1.5 min-w-0 w-full">
                @foreach ($displayCrumbs as $i => $crumb)
                    @php
                        $isLast = $loop->last;
                    @endphp
                    <li class="{{ $isLast ? 'flex items-center min-w-0' : 'hidden sm:flex items-center' }}">
                        <div class="flex items-center min-w-0">
                            @if ($i > 0)
                                <x-icons.angle-right class="mx-1 h-3 w-3 text-zinc-400 dark:text-zinc-500 {{ $isLast ? 'hidden sm:block' : '' }}" />
                            @endif

                            @if (isset($crumb['is_ellipsis']))
                                <span class="text-[9px] font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 leading-none">
                                    ...
                                </span>
                            @else
                                @php
                                    $isFirst = $i === 0;
                                    $crumbTitle = count($crumbs) > 2 && $isFirst ? '' : $crumb['title'];
                                @endphp

                                @if (!$isLast && !empty($crumb['url']))
                                    <a href="{{ $crumb['url'] }}"
                                        class="group flex items-center leading-none whitespace-nowrap text-[9px] font-bold uppercase tracking-widest text-zinc-500 transition-all hover:text-red-600 dark:text-zinc-400 dark:hover:text-red-500">
                                        @if ($isFirst)
                                            <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5 transition-transform group-hover:scale-110" />
                                        @endif
                                        <span class="leading-none inline-block max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                    </a>
                                @elseif (!$isLast)
                                    <span
                                        class="flex items-center leading-none whitespace-nowrap text-[9px] font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                        @if ($isFirst)
                                            <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5" />
                                        @endif
                                        <span class="leading-none inline-block max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                    </span>
                                @else
                                    <span
                                        class="flex items-center leading-none whitespace-nowrap text-[9px] font-black uppercase tracking-widest text-red-600 dark:text-red-500 min-w-0">
                                        @if ($isFirst)
                                            <x-icons.home class="{{ $crumbTitle ? 'me-1.5' : '' }} h-3.5 w-3.5" />
                                        @endif
                                        <span class="leading-none inline-block max-w-20 sm:max-w-30 lg:max-w-50 truncate" title="{{ $crumbTitle }}">{{ $crumbTitle }}</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </nav>
    </template>
</div>
