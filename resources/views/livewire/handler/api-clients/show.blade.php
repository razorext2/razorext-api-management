{{-- Goal: Detail View and Request Logs for an API Client, Livewire: Yes, Alpine: Yes --}}
<div class="space-y-6" @if($autoRefresh) wire:poll.10s @endif>
    {{-- Header Banner & Action Bar --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-tr from-red-600 to-rose-500 shadow-lg shadow-red-500/20 text-white">
                <x-icons.code class="h-6 w-6" />
            </div>
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-2xl">
                        {{ $client->name }}
                    </h1>
                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $client->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/20' : 'bg-zinc-500/10 text-zinc-600 dark:bg-zinc-500/20 dark:text-zinc-400 border border-zinc-500/20' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $client->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Slug: <code class="font-mono text-zinc-700 dark:text-zinc-300 font-semibold">{{ $client->slug ?? '-' }}</code>
                    &bull; Dibuat {{ \Illuminate\Support\Carbon::parse($client->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <x-button.secondary href="{{ route('api-clients.index') }}" wire:navigate class="text-xs">
                <x-slot name="icon">
                    <x-icons.chevron-left class="h-4 w-4" />
                </x-slot>
                Kembali
            </x-button.secondary>

            @can('api-clients-edit')
                <x-button.primary href="{{ route('api-clients.edit', $client->id) }}" wire:navigate class="text-xs">
                    <x-slot name="icon">
                        <x-icons.user-setting class="h-4 w-4" />
                    </x-slot>
                    Edit Konfigurasi
                </x-button.primary>
            @endcan

            <button type="button"
                wire:click="toggleAutoRefresh"
                class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-medium transition-all {{ $autoRefresh ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/30 dark:bg-emerald-500/20 dark:text-emerald-400 shadow-sm' : 'bg-white text-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-800 dark:hover:bg-zinc-800' }}">
                <span class="relative flex h-2 w-2">
                    @if($autoRefresh)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    @endif
                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $autoRefresh ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                </span>
                <span>Live Refresh {{ $autoRefresh ? '(10s)' : '' }}</span>
            </button>
        </div>
    </div>

    {{-- Client Overview & API Key Card --}}
    <div class="rounded-2xl border p-5 transition-all duration-200"
        x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            {{-- Left Column: API Key Box --}}
            <div class="lg:col-span-2 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-red-600 dark:text-red-400">Header Kredensial (X-API-KEY)</span>
                    <span class="text-[11px] text-zinc-400">Gunakan key ini pada header HTTP request</span>
                </div>
                <div class="flex items-center gap-2" x-data="{ copied: false, showKey: false }">
                    <div class="relative flex-1">
                        <input :type="showKey ? 'text' : 'password'"
                            readonly
                            value="{{ $client->api_key }}"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50/75 px-3.5 py-2.5 font-mono text-xs font-semibold text-zinc-900 select-all focus:outline-none dark:border-zinc-800 dark:bg-zinc-900/90 dark:text-zinc-100" />
                        <button type="button"
                            @click="showKey = !showKey"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 text-xs p-1">
                            <span x-text="showKey ? 'Sembunyikan' : 'Lihat'"></span>
                        </button>
                    </div>
                    <button type="button"
                        @click="navigator.clipboard.writeText('{{ $client->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-zinc-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-zinc-700 shadow-xs hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-all">
                        <span x-show="!copied">📋 Salin Key</span>
                        <span x-show="copied" class="text-emerald-600 dark:text-emerald-400">✓ Tersalin!</span>
                    </button>
                </div>
                @if($client->description)
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Deskripsi:</span> {{ $client->description }}
                    </p>
                @endif
            </div>

            {{-- Right Column: Rate Limit & Security Settings --}}
            <div class="grid grid-cols-2 gap-3 border-t lg:border-t-0 lg:border-l border-zinc-200 dark:border-zinc-800/80 pt-4 lg:pt-0 lg:pl-5">
                <div class="space-y-1">
                    <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Rate Limit</span>
                    <p class="text-sm font-bold text-zinc-900 dark:text-white">
                        {{ $client->rate_limit_per_minute }} <span class="text-xs font-normal text-zinc-500">req/menit</span>
                    </p>
                </div>
                <div class="space-y-1">
                    <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Terakhir Digunakan</span>
                    <p class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ $client->last_used_at ? \Illuminate\Support\Carbon::parse($client->last_used_at)->locale('id')->diffForHumans() : 'Belum pernah' }}
                    </p>
                </div>
                <div class="col-span-2 space-y-1">
                    <span class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">IP Whitelist</span>
                    <div class="flex flex-wrap gap-1 mt-0.5">
                        @if(!empty($client->allowed_ips))
                            @foreach($client->allowed_ips as $ip)
                                <span class="font-mono text-[11px] bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300">{{ $ip }}</span>
                            @endforeach
                        @else
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Semua IP Diizinkan (Publik)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Telemetry Metric Cards --}}
    <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Requests Card --}}
        <div class="rounded-2xl border p-4 transition-all duration-200 hover:shadow-md"
            x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Request</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                    <x-icons.clockwise class="h-4 w-4" />
                </span>
            </div>
            <div class="mt-2">
                <div class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">
                    {{ number_format($totalRequests) }}
                </div>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">Semua request tercatat</p>
            </div>
        </div>

        {{-- Success Rate Card --}}
        <div class="rounded-2xl border p-4 transition-all duration-200 hover:shadow-md"
            x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Tingkat Keberhasilan (2xx)</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                    <x-icons.check-circle class="h-4 w-4" />
                </span>
            </div>
            <div class="mt-2">
                <div class="text-2xl font-black tracking-tight {{ $successRate >= 95 ? 'text-emerald-600 dark:text-emerald-400' : ($successRate >= 80 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                    {{ $successRate }}%
                </div>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">{{ number_format($successCount) }} sukses dari {{ number_format($totalRequests) }}</p>
            </div>
        </div>

        {{-- Error Rate Card --}}
        <div class="rounded-2xl border p-4 transition-all duration-200 hover:shadow-md"
            x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Error (4xx / 5xx)</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                    <x-icons.bell class="h-4 w-4" />
                </span>
            </div>
            <div class="mt-2">
                <div class="text-2xl font-black tracking-tight text-rose-600 dark:text-rose-400">
                    {{ number_format($error4xxCount + $error5xxCount) }}
                </div>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">{{ $error4xxCount }} client / {{ $error5xxCount }} server</p>
            </div>
        </div>

        {{-- Latency Card --}}
        <div class="rounded-2xl border p-4 transition-all duration-200 hover:shadow-md"
            x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Rata-rata Latensi</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400">
                    <x-icons.cpu class="h-4 w-4" />
                </span>
            </div>
            <div class="mt-2">
                <div class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">
                    {{ $avgExecutionTime }} <span class="text-xs font-bold text-zinc-400">ms</span>
                </div>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">Waktu eksekusi rata-rata</p>
            </div>
        </div>
    </div>

    {{-- Request Logs Section --}}
    <div class="rounded-2xl border p-5 transition-all duration-200 space-y-4"
        x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        
        {{-- Section Title and Log Toolbar --}}
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between border-b border-zinc-200 dark:border-zinc-800 pb-4">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span>Riwayat Hit API (Request Logs)</span>
                    <span class="rounded-full bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                        {{ $logs->total() }} Log
                    </span>
                </h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftar rekaman HTTP request yang menggunakan API key klien ini</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @can('api-clients-delete')
                    @if($totalRequests > 0)
                        <button type="button"
                            x-data
                            @click="Swal.fire({
                                title: 'Bersihkan Riwayat Log?',
                                text: 'Seluruh catatan riwayat request untuk client ini akan dihapus permanen!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc2626',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Bersihkan!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.clearLogs();
                                }
                            })"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 dark:border-rose-900/50 dark:bg-rose-950/30 dark:text-rose-300 dark:hover:bg-rose-950/60 transition-all">
                            <x-icons.trash-bin class="h-3.5 w-3.5" />
                            <span>Bersihkan Log</span>
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-6">
            {{-- Search --}}
            <div class="lg:col-span-2 relative">
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari endpoint, IP, user agent..."
                    class="w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-xs text-zinc-800 placeholder-zinc-400 shadow-xs focus:border-red-500 focus:outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200 dark:placeholder-zinc-500" />
            </div>

            {{-- Filter Method --}}
            <div>
                <select wire:model.live="method"
                    class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs text-zinc-800 shadow-xs focus:border-red-500 focus:outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                    <option value="">Semua Method</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>

            {{-- Filter Status Code --}}
            <div>
                <select wire:model.live="status"
                    class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs text-zinc-800 shadow-xs focus:border-red-500 focus:outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                    <option value="">Semua Status</option>
                    <option value="2xx">2xx Sukses</option>
                    <option value="4xx">4xx Client Error</option>
                    <option value="5xx">5xx Server Error</option>
                    <option value="200">200 OK</option>
                    <option value="400">400 Bad Request</option>
                    <option value="401">401 Unauthorized</option>
                    <option value="403">403 Forbidden</option>
                    <option value="429">429 Rate Limit Exceeded</option>
                    <option value="500">500 Server Error</option>
                </select>
            </div>

            {{-- Filter Period --}}
            <div>
                <select wire:model.live="period"
                    class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs text-zinc-800 shadow-xs focus:border-red-500 focus:outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                    <option value="all">Semua Waktu</option>
                    <option value="today">Hari Ini</option>
                    <option value="7d">7 Hari Terakhir</option>
                    <option value="30d">30 Hari Terakhir</option>
                </select>
            </div>

            {{-- Filter Reset Button --}}
            <div class="flex items-center gap-1.5">
                <button type="button"
                    wire:click="resetFilters"
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2 text-xs font-semibold text-zinc-600 hover:bg-zinc-100 dark:border-zinc-800 dark:bg-zinc-800/80 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-all text-center">
                    Reset Filter
                </button>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-800">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-left text-xs">
                <thead class="bg-zinc-50/80 dark:bg-zinc-900/60 font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th scope="col" class="py-3 px-3.5">Waktu</th>
                        <th scope="col" class="py-3 px-3.5">Method</th>
                        <th scope="col" class="py-3 px-3.5">Endpoint</th>
                        <th scope="col" class="py-3 px-3.5">Status</th>
                        <th scope="col" class="py-3 px-3.5">Latensi</th>
                        <th scope="col" class="py-3 px-3.5">IP Address</th>
                        <th scope="col" class="py-3 px-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 bg-white dark:bg-dark-primary font-medium">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                            {{-- Timestamp --}}
                            <td class="py-3 px-3.5 whitespace-nowrap text-zinc-600 dark:text-zinc-400 font-mono text-[11px]">
                                <div>{{ \Illuminate\Support\Carbon::parse($log->created_at)->locale('id')->isoFormat('D MMM Y, HH:mm:ss') }}</div>
                                <div class="text-[10px] text-zinc-400">{{ \Illuminate\Support\Carbon::parse($log->created_at)->locale('id')->diffForHumans() }}</div>
                            </td>

                            {{-- HTTP Method --}}
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                @php
                                    $methodColors = [
                                        'GET'    => 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border-emerald-500/20',
                                        'POST'   => 'bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 border-blue-500/20',
                                        'PUT'    => 'bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 border-amber-500/20',
                                        'PATCH'  => 'bg-purple-500/10 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400 border-purple-500/20',
                                        'DELETE' => 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 border-rose-500/20',
                                    ];
                                    $mColor = $methodColors[$log->method] ?? 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300';
                                @endphp
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 font-mono text-[11px] font-bold {{ $mColor }}">
                                    {{ $log->method }}
                                </span>
                            </td>

                            {{-- Endpoint --}}
                            <td class="py-3 px-3.5 text-zinc-900 dark:text-zinc-100">
                                <div class="flex items-center gap-1.5">
                                    <code class="font-mono text-xs font-semibold text-zinc-800 dark:text-zinc-200">
                                        /{{ ltrim($log->endpoint, '/') }}
                                    </code>
                                </div>
                                @if($log->error_message)
                                    <p class="text-[11px] text-rose-500 dark:text-rose-400 line-clamp-1 mt-0.5" title="{{ $log->error_message }}">
                                        {{ $log->error_message }}
                                    </p>
                                @endif
                            </td>

                            {{-- Status Code Badge --}}
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                @php
                                    $code = (int) $log->status_code;
                                    if ($code >= 200 && $code < 300) {
                                        $sBadge = 'bg-emerald-500/10 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-500/20';
                                    } elseif ($code === 429) {
                                        $sBadge = 'bg-amber-500/15 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 border-amber-500/30 font-bold';
                                    } elseif ($code >= 400 && $code < 500) {
                                        $sBadge = 'bg-orange-500/10 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300 border-orange-500/20';
                                    } else {
                                        $sBadge = 'bg-rose-500/10 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border-rose-500/20 font-bold';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-semibold font-mono {{ $sBadge }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $code < 300 ? 'bg-emerald-500' : ($code < 500 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    {{ $code }}
                                </span>
                            </td>

                            {{-- Execution Time --}}
                            <td class="py-3 px-3.5 whitespace-nowrap font-mono text-[11px]">
                                @php
                                    $time = (float) $log->execution_time_ms;
                                    $timeClass = $time < 50 ? 'text-emerald-600 dark:text-emerald-400' : ($time < 200 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400 font-semibold');
                                @endphp
                                <span class="{{ $timeClass }}">{{ $time }} ms</span>
                            </td>

                            {{-- IP Address --}}
                            <td class="py-3 px-3.5 whitespace-nowrap font-mono text-[11px] text-zinc-600 dark:text-zinc-400">
                                {{ $log->ip_address ?? '-' }}
                            </td>

                            {{-- Action Inspect --}}
                            <td class="py-3 px-3.5 whitespace-nowrap text-center">
                                <button type="button"
                                    wire:click="inspectLog({{ $log->id }})"
                                    class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-white px-2.5 py-1 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-all">
                                    <x-icons.eye class="h-3 w-3" />
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-400 dark:bg-zinc-800 dark:text-zinc-500">
                                        <x-icons.rectangle-list class="h-6 w-6" />
                                    </div>
                                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Belum ada rekaman log request</p>
                                    <p class="text-xs text-zinc-400 max-w-sm">
                                        @if(!empty($search) || !empty($method) || !empty($status) || $period !== 'all')
                                            Tidak ada data log yang cocok dengan filter pencarian saat ini.
                                        @else
                                            Request HTTP yang dikirim dengan API Key ini akan otomatis dicatat dan ditampilkan di tabel ini.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination & Page Info --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                <span>Tampilkan</span>
                <select wire:model.live="perPage"
                    class="rounded-lg border border-zinc-200 bg-white px-2 py-1 text-xs text-zinc-800 shadow-xs focus:border-red-500 focus:outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>data per halaman</span>
            </div>

            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    {{-- Inspect Log Modal Popup --}}
    @if($selectedLog)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            x-data
            @keydown.escape.window="$wire.closeInspectModal()">
            <div class="w-full max-w-2xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-2xl dark:border-zinc-800 dark:bg-zinc-950 space-y-5 animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-red-600/10 text-red-600 dark:bg-red-600/20 dark:text-red-400 font-bold font-mono text-xs">
                            #{{ $selectedLog->id }}
                        </span>
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Detail Rekaman Request API</h4>
                            <p class="text-[11px] text-zinc-400 font-mono">{{ \Illuminate\Support\Carbon::parse($selectedLog->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm:ss') }}</p>
                        </div>
                    </div>
                    <button type="button"
                        wire:click="closeInspectModal"
                        class="rounded-xl p-1.5 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-zinc-900 dark:hover:text-zinc-200 transition-all">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">Method & Endpoint</span>
                        <div class="flex items-center gap-2 font-mono">
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $selectedLog->method }}</span>
                            <span class="text-zinc-800 dark:text-zinc-200">/{{ ltrim($selectedLog->endpoint, '/') }}</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">HTTP Status & Latensi</span>
                        <div class="flex items-center gap-3 font-mono">
                            <span class="font-bold {{ $selectedLog->status_code < 300 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $selectedLog->status_code }}</span>
                            <span class="text-zinc-500">&bull;</span>
                            <span class="text-zinc-700 dark:text-zinc-300">{{ $selectedLog->execution_time_ms }} ms</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">Client IP Address</span>
                        <p class="font-mono text-zinc-800 dark:text-zinc-200">{{ $selectedLog->ip_address ?? 'Unknown' }}</p>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">API Client ID</span>
                        <p class="font-mono text-zinc-800 dark:text-zinc-200">{{ $client->name }} (ID: {{ $selectedLog->api_client_id }})</p>
                    </div>

                    <div class="col-span-2 space-y-1">
                        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">User-Agent Header</span>
                        <p class="rounded-xl border border-zinc-200 bg-zinc-50 p-2.5 font-mono text-[11px] text-zinc-700 break-all dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                            {{ $selectedLog->user_agent ?? 'Header User-Agent tidak tersedia' }}
                        </p>
                    </div>

                    {{-- HTTP Request Headers (Forwarders & Payload) --}}
                    <div class="col-span-2 space-y-1.5" x-data="{ copiedHeaders: false }">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">
                                HTTP Request Headers (Forwarders & Metadata)
                            </span>
                            @if(!empty($selectedLog->request_headers))
                                <button type="button"
                                    @click="navigator.clipboard.writeText(JSON.stringify(@js($selectedLog->request_headers), null, 2)); copiedHeaders = true; setTimeout(() => copiedHeaders = false, 2000)"
                                    class="text-[11px] font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    <span x-show="!copiedHeaders">📋 Salin Headers JSON</span>
                                    <span x-show="copiedHeaders" class="text-emerald-600 dark:text-emerald-400">✓ JSON Tersalin!</span>
                                </button>
                            @endif
                        </div>
                        @if(!empty($selectedLog->request_headers))
                            <div class="max-h-56 overflow-y-auto rounded-xl border border-zinc-200 bg-zinc-900 p-3 font-mono text-[11px] text-emerald-400 dark:border-zinc-800 dark:bg-black/60 shadow-inner">
                                <pre class="whitespace-pre-wrap break-all">{{ json_encode($selectedLog->request_headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @else
                            <p class="rounded-xl border border-zinc-200 bg-zinc-50 p-2.5 font-mono text-[11px] text-zinc-500 italic dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                                Header lengkap tidak terekam pada log request versi lama.
                            </p>
                        @endif
                    </div>

                    @if($selectedLog->error_message)
                        <div class="col-span-2 space-y-1">
                            <span class="text-[11px] font-semibold text-rose-500 uppercase tracking-wider">Pesan Kesalahan (Error Message)</span>
                            <p class="rounded-xl border border-rose-200 bg-rose-50 p-2.5 font-mono text-[11px] text-rose-700 break-all dark:border-rose-900/50 dark:bg-rose-950/40 dark:text-rose-300">
                                {{ $selectedLog->error_message }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-2">
                    <x-button.secondary type="button" wire:click="closeInspectModal" class="text-xs">
                        Tutup
                    </x-button.secondary>
                </div>
            </div>
        </div>
    @endif
</div>
