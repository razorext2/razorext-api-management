<section>
    {{-- Trigger Button --}}
    <x-button.warning class="group w-full justify-start gap-4 rounded-xl p-4" type="button"
        wire:click="$set('showLogUpdateModal', true)">
        <x-slot name="icon">
            <div
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-600 text-white shadow-sm transition-transform duration-300 group-hover:scale-110 dark:bg-amber-500">
                <x-icons.clock class="h-4 w-4" />
            </div>
        </x-slot>

        <div class="flex flex-col">
            <span class="text-sm font-medium">View Update Log</span>
        </div>
    </x-button.warning>

    <x-modal.base-modal show="showLogUpdateModal" maxWidth="2xl" title="Update Log"
        iconContainerClass="bg-red-600 shadow-red-500/20">

        <x-slot name="icon">
            <x-icons.code class="h-5 w-5" />
        </x-slot>

        {{-- Stats Bar --}}
        @php
            $stats = $this->repositoryStats();
            $firstCommit = $stats['first_commit_date']
                ? \Carbon\Carbon::parse($stats['first_commit_date'])->locale('id')->isoFormat('D MMMM YYYY')
                : null;
        @endphp
        <div class="mb-5 flex flex-col gap-0.5">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                Menampilkan 10 perubahan sistem terakhir
            </p>
            @if ($stats['total_commits'] > 0)
                <p class="text-[10px] font-bold uppercase tracking-widest text-red-600 dark:text-red-400">
                    Total {{ number_format($stats['total_commits'], 0, ',', '.') }} Commit
                    @if ($firstCommit)
                        • Sejak {{ $firstCommit }}
                    @endif
                </p>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="relative border-l-2 border-zinc-200 pl-8 dark:border-zinc-800">
            @foreach ($this->logHistories() as $row)
                @php
                    $commit = $row['commit'];
                    $message = $commit['message'] ?? '-';
                    $name = $commit['committer']['name'] ?? '-';
                    $email = $commit['committer']['email'] ?? '-';
                    $date = \Carbon\Carbon::parse($commit['committer']['date'])->timezone('Asia/Jakarta')->locale('id');
                @endphp

                <div class="relative mb-10 last:mb-0">
                    {{-- Milestone Dot --}}
                    <div
                        class="absolute -left-10.25 flex h-6 w-6 items-center justify-center rounded-full border-4 border-white bg-red-600 shadow-sm ring-4 ring-zinc-50 dark:border-zinc-900 dark:ring-zinc-900">
                        <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                    </div>

                    {{-- Date Label --}}
                    <time
                        class="mb-2 block text-xs font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        {{ $date->translatedFormat('d F Y • H:i') }} WIB
                    </time>

                    {{-- Commit Card --}}
                    <div
                        class="group rounded-2xl border border-zinc-200 bg-zinc-50/50 p-5 transition-all hover:border-red-200 hover:bg-white hover:shadow-xl hover:shadow-red-500/5 dark:border-zinc-800 dark:bg-zinc-800/30 dark:hover:border-red-900/50 dark:hover:bg-zinc-800/60">
                        <h3 class="font-bold leading-snug text-zinc-900 dark:text-white sm:text-lg">
                            {{ $message }}
                        </h3>

                        {{-- File Changes --}}
                        @if (!empty($row['detailed_files']))
                            <div
                                class="scrollbar-thin scrollbar-thumb-zinc-200 dark:scrollbar-thumb-zinc-800 mt-4 max-h-36 overflow-y-auto pr-2">
                                <div class="flex flex-col gap-1">
                                    @foreach ($row['detailed_files'] as $file)
                                        @php
                                            $statusData = match ($file['status']) {
                                                'added' => [
                                                    'color' => 'text-emerald-600 dark:text-emerald-400',
                                                    'bg' => 'bg-emerald-50 dark:bg-emerald-950/20',
                                                    'icon' => '+',
                                                ],
                                                'removed' => [
                                                    'color' => 'text-rose-600 dark:text-rose-400',
                                                    'bg' => 'bg-rose-50 dark:bg-rose-950/20',
                                                    'icon' => '-',
                                                ],
                                                'modified' => [
                                                    'color' => 'text-amber-600 dark:text-amber-400',
                                                    'bg' => 'bg-amber-50 dark:bg-amber-950/20',
                                                    'icon' => 'M',
                                                ],
                                                'renamed' => [
                                                    'color' => 'text-sky-600 dark:text-sky-400',
                                                    'bg' => 'bg-sky-50 dark:bg-sky-950/20',
                                                    'icon' => 'R',
                                                ],
                                                default => [
                                                    'color' => 'text-zinc-600 dark:text-zinc-400',
                                                    'bg' => 'bg-zinc-50 dark:bg-zinc-950/20',
                                                    'icon' => '•',
                                                ],
                                            };
                                        @endphp
                                        <div title="{{ $file['name'] }}"
                                            class="flex items-center gap-2 rounded-lg border border-zinc-200/50 bg-white p-2 transition-all hover:border-zinc-200 dark:border-zinc-800/50 dark:hover:border-zinc-700"
                                            x-bind:class="dynamicBg ?
                                                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                                                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                                            <div
                                                class="{{ $statusData['bg'] }} {{ $statusData['color'] }} flex h-5 w-5 shrink-0 items-center justify-center rounded-md text-[10px] font-black">
                                                {{ $statusData['icon'] }}
                                            </div>
                                            <span
                                                class="truncate text-[11px] font-medium text-zinc-600 dark:text-zinc-400">
                                                {{ $file['name'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Committer --}}
                        <div
                            class="mt-4 flex flex-wrap items-center gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-800/50">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-200 text-[10px] font-bold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">
                                {{ strtoupper(substr($name, 0, 2)) }}
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-xs font-bold text-zinc-700 dark:text-zinc-300">{{ $name }}</span>
                                <span class="text-[10px] text-zinc-500 dark:text-zinc-500">{{ $email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <x-button.secondary wire:click="$set('showLogUpdateModal', false)" class="w-full justify-center">
                Selesai Membaca
            </x-button.secondary>
        </x-slot>
    </x-modal.base-modal>
</section>
