{{-- Goal: Interactive Apriori Data Mining Sandbox & Playground --}}
<div class="space-y-6" x-data="{ activeTab: 'rules' }">
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Apriori Data Mining Sandbox</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Playground pengujian algoritma Market Basket Analysis & Association Rules secara interaktif</p>
        </div>

        {{-- Preset Buttons --}}
        <div class="flex items-center gap-1.5 rounded-xl border border-zinc-200 bg-white p-1 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
            <span class="px-2 text-xs font-semibold text-zinc-400">Preset:</span>
            <button type="button" wire:click="loadPreset('retail')" class="rounded-lg px-2.5 py-1 text-xs font-medium transition-colors {{ $selected_preset === 'retail' ? 'bg-red-600 text-white font-bold' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                🛒 Retail / Minimarket
            </button>
            <button type="button" wire:click="loadPreset('cafe')" class="rounded-lg px-2.5 py-1 text-xs font-medium transition-colors {{ $selected_preset === 'cafe' ? 'bg-red-600 text-white font-bold' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                ☕ Coffee Shop
            </button>
            <button type="button" wire:click="loadPreset('ecommerce')" class="rounded-lg px-2.5 py-1 text-xs font-medium transition-colors {{ $selected_preset === 'ecommerce' ? 'bg-red-600 text-white font-bold' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                💻 E-Commerce
            </button>
        </div>
    </div>

    {{-- Main Grid: Controls & Input on Left, Results on Right --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left: Parameter Settings & Input Transactions --}}
        <div class="space-y-5 lg:col-span-4">
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">1. Parameter Threshold</h3>
                
                <div class="mt-4 space-y-4">
                    {{-- Min Support --}}
                    <div>
                        <div class="flex items-center justify-between text-xs font-medium text-zinc-700 dark:text-zinc-300">
                            <span>Minimum Support:</span>
                            <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ round($min_support * 100) }}% ({{ $min_support }})</span>
                        </div>
                        <input type="range" min="0.05" max="0.95" step="0.05" wire:model.live.debounce.300ms="min_support" class="mt-2 w-full accent-red-600" />
                        <p class="text-[10px] text-zinc-400">Ambang batas minimum frekuensi kemunculan itemset.</p>
                    </div>

                    {{-- Min Confidence --}}
                    <div>
                        <div class="flex items-center justify-between text-xs font-medium text-zinc-700 dark:text-zinc-300">
                            <span>Minimum Confidence:</span>
                            <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ round($min_confidence * 100) }}% ({{ $min_confidence }})</span>
                        </div>
                        <input type="range" min="0.1" max="1.0" step="0.05" wire:model.live.debounce.300ms="min_confidence" class="mt-2 w-full accent-red-600" />
                        <p class="text-[10px] text-zinc-400">Tingkat keyakinan / kepastian aturan asosiasi.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">2. Dataset Transaksi</h3>
                    <span class="text-[11px] text-zinc-400">1 baris = 1 transaksi</span>
                </div>

                <div class="mt-3">
                    <textarea wire:model="transactions_text" rows="8" class="w-full rounded-xl border-zinc-200 bg-zinc-50 font-mono text-xs text-zinc-800 focus:border-red-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-600/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200" placeholder="Item1, Item2, Item3..."></textarea>
                    <p class="mt-1 text-[11px] text-zinc-400">Pisahkan nama item dalam tiap transaksi menggunakan tanda koma (,).</p>
                </div>

                <div class="mt-4">
                    <x-button.primary wire:click="runCalculation" class="w-full justify-center">
                        <x-slot name="icon">
                            <x-icons.play class="h-4 w-4" />
                        </x-slot>
                        Hitung Algoritma Apriori
                    </x-button.primary>
                </div>
            </div>
        </div>

        {{-- Right: Result Analytics & Rules Display --}}
        <div class="space-y-5 lg:col-span-8">
            @if ($result)
                {{-- Metric Overview Cards --}}
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                        <div class="text-[11px] font-medium uppercase text-zinc-400">Total Transaksi</div>
                        <div class="mt-1 text-2xl font-black text-zinc-900 dark:text-white">{{ $result['summary']['total_transactions'] }}</div>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                        <div class="text-[11px] font-medium uppercase text-zinc-400">Unique Items</div>
                        <div class="mt-1 text-2xl font-black text-zinc-900 dark:text-white">{{ $result['summary']['total_unique_items'] }}</div>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                        <div class="text-[11px] font-medium uppercase text-zinc-400">Frequent Itemsets</div>
                        <div class="mt-1 text-2xl font-black text-red-600 dark:text-red-400">{{ $result['summary']['total_frequent_itemsets'] }}</div>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                        <div class="text-[11px] font-medium uppercase text-zinc-400">Latensi Engine</div>
                        <div class="mt-1 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $result['execution_time_ms'] }} <span class="text-xs font-normal">ms</span></div>
                    </div>
                </div>

                {{-- Result Tabs --}}
                <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-dark-primary">
                    <div class="flex border-b border-zinc-200 px-4 dark:border-zinc-800">
                        <button type="button" @click="activeTab = 'rules'" :class="activeTab === 'rules' ? 'border-red-600 text-red-600 dark:border-red-500 dark:text-red-400 font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400'" class="flex items-center gap-2 border-b-2 py-3.5 px-4 text-xs tracking-wide transition-all">
                            <span>📜 Association Rules</span>
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700 dark:bg-red-950/60 dark:text-red-400">{{ count($result['association_rules']) }}</span>
                        </button>
                        <button type="button" @click="activeTab = 'itemsets'" :class="activeTab === 'itemsets' ? 'border-red-600 text-red-600 dark:border-red-500 dark:text-red-400 font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400'" class="flex items-center gap-2 border-b-2 py-3.5 px-4 text-xs tracking-wide transition-all">
                            <span>📦 Frequent Itemsets</span>
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ count($result['frequent_itemsets']) }}</span>
                        </button>
                        <button type="button" @click="activeTab = 'json'" :class="activeTab === 'json' ? 'border-red-600 text-red-600 dark:border-red-500 dark:text-red-400 font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400'" class="flex items-center gap-2 border-b-2 py-3.5 px-4 text-xs tracking-wide transition-all">
                            <span>⚡ Raw JSON API Response</span>
                        </button>
                    </div>

                    <div class="p-5">
                        {{-- Tab 1: Association Rules Table --}}
                        <div x-show="activeTab === 'rules'" class="space-y-4">
                            @if (empty($result['association_rules']))
                                <div class="py-12 text-center">
                                    <p class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">Tidak ada aturan asosiasi yang terbentuk dengan threshold saat ini.</p>
                                    <p class="mt-1 text-xs text-zinc-400">Coba turunkan nilai Minimum Support atau Minimum Confidence di sebelah kiri.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:border-zinc-800 dark:bg-zinc-900/50 dark:text-zinc-400">
                                            <tr>
                                                <th class="py-3 px-3">#</th>
                                                <th class="py-3 px-3">Antecedent (Jika Beli)</th>
                                                <th class="py-3 px-3"></th>
                                                <th class="py-3 px-3">Consequent (Maka Beli)</th>
                                                <th class="py-3 px-3 text-center">Support</th>
                                                <th class="py-3 px-3 text-center">Confidence</th>
                                                <th class="py-3 px-3 text-center">Lift Ratio</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                                            @foreach ($result['association_rules'] as $idx => $rule)
                                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30">
                                                    <td class="py-3 px-3 font-mono text-zinc-400">{{ $idx + 1 }}</td>
                                                    <td class="py-3 px-3">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach ($rule['antecedent'] as $ant)
                                                                <span class="rounded bg-blue-50 px-2 py-0.5 font-semibold text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">{{ $ant }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-1 text-center font-bold text-zinc-400">➔</td>
                                                    <td class="py-3 px-3">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach ($rule['consequent'] as $cons)
                                                                <span class="rounded bg-emerald-50 px-2 py-0.5 font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">{{ $cons }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-3 text-center font-mono font-semibold">{{ $rule['support_percent'] }}</td>
                                                    <td class="py-3 px-3 text-center font-mono font-bold text-red-600 dark:text-red-400">{{ $rule['confidence_percent'] }}</td>
                                                    <td class="py-3 px-3 text-center">
                                                        <span class="rounded px-2 py-0.5 font-mono text-[11px] font-bold {{ $rule['is_valid_lift'] ? 'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                                            {{ $rule['lift_ratio'] }} {{ $rule['is_valid_lift'] ? '🔥' : '' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Tab 2: Frequent Itemsets --}}
                        <div x-show="activeTab === 'itemsets'" class="space-y-4">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-bold uppercase tracking-wider text-zinc-500 dark:border-zinc-800 dark:bg-zinc-900/50 dark:text-zinc-400">
                                        <tr>
                                            <th class="py-3 px-3">Ukuran (k)</th>
                                            <th class="py-3 px-3">Kombinasi Itemset</th>
                                            <th class="py-3 px-3 text-center">Frekuensi (Count)</th>
                                            <th class="py-3 px-3 text-center">Support (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                                        @foreach ($result['frequent_itemsets'] as $itemset)
                                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30">
                                                <td class="py-3 px-3 font-mono font-bold text-zinc-500">{{ $itemset['k'] }}-itemset</td>
                                                <td class="py-3 px-3">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($itemset['items'] as $it)
                                                            <span class="rounded bg-zinc-100 px-2 py-0.5 font-medium text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">{{ $it }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="py-3 px-3 text-center font-mono font-semibold">{{ $itemset['count'] }} kali</td>
                                                <td class="py-3 px-3 text-center font-mono font-bold text-red-600 dark:text-red-400">{{ $itemset['support_percent'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab 3: Raw JSON --}}
                        <div x-show="activeTab === 'json'" class="space-y-2">
                            <div class="relative">
                                <pre class="max-h-96 overflow-y-auto rounded-xl border border-zinc-800 bg-zinc-950 p-4 font-mono text-xs text-emerald-400"><code>{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
