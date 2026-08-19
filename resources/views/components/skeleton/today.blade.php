{{-- Goal: Skeleton loader for today's check-in and check-out attendance grid, Caller: Today livewire component, Deps: - --}}
<div class="flex flex-col gap-4">
    {{-- Unified Filter Bar Skeleton --}}
    <div
        class="flex animate-pulse flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary sm:flex-row sm:items-center sm:justify-between md:p-6">
        <div class="space-y-2">
            <div class="h-5 w-48 rounded bg-zinc-200 dark:bg-zinc-800"></div>
            <div class="h-3 w-64 rounded bg-zinc-100 dark:bg-zinc-900"></div>
        </div>
        <div class="flex w-full gap-3 sm:w-auto">
            <div class="h-10 w-full rounded-xl bg-zinc-200 dark:bg-zinc-800 sm:w-44"></div>
            <div class="h-10 w-full rounded-xl bg-zinc-200 dark:bg-zinc-800 sm:w-44"></div>
            <div class="h-10 w-24 rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>
        </div>
    </div>

    {{-- Grid Skeletons --}}
    <div class="flex animate-pulse flex-col gap-4">
        @for ($g = 1; $g <= 2; $g++)
            {{-- Grid Card Section --}}
            <div
                class="flex w-full flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary lg:p-6">
                {{-- Header Skeleton --}}
                <div class="flex items-center justify-between border-b border-zinc-200 pb-5 dark:border-zinc-800/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>
                        <div class="space-y-1">
                            <div class="h-5 w-32 rounded bg-zinc-200 dark:bg-zinc-800"></div>
                            <div class="h-3 w-48 rounded bg-zinc-100 dark:bg-zinc-900"></div>
                        </div>
                    </div>
                    <div class="h-8 w-24 rounded-2xl bg-zinc-200 dark:bg-zinc-800"></div>
                </div>

                {{-- Cards Grid Skeleton --}}
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                    @for ($i = 1; $i <= 4; $i++)
                        <div
                            class="flex flex-col overflow-hidden rounded-2xl border border-zinc-100 bg-white p-0 dark:border-zinc-800/50 dark:bg-zinc-900/50 lg:flex-row">
                            {{-- Image Block Skeleton --}}
                            <div class="h-44 w-full shrink-0 bg-zinc-200 dark:bg-zinc-800 lg:h-32 lg:w-32"></div>
                            {{-- Info Block Skeleton --}}
                            <div class="flex flex-1 flex-col justify-between gap-3 p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="h-4 w-28 rounded bg-zinc-200 dark:bg-zinc-800"></div>
                                        <div class="h-3.5 w-8 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>
                                    </div>
                                    <div class="h-3.5 w-36 rounded bg-zinc-200 dark:bg-zinc-800"></div>
                                </div>
                                <div class="h-3 w-48 rounded bg-zinc-100 dark:bg-zinc-900"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endfor
    </div>
</div>
