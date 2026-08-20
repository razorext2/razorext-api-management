<?php

namespace App\Services\DataMining;

class AprioriService
{
    /**
     * Menjalankan perhitungan algoritma Apriori secara lengkap.
     *
     * @param  array<int, array<int, string>>  $transactions
     * @param  float  $minSupport  Nilai min support (antara 0.0 - 1.0)
     * @param  float  $minConfidence  Nilai min confidence (antara 0.0 - 1.0)
     * @return array<string, mixed>
     */
    public function calculate(array $transactions, float $minSupport = 0.2, float $minConfidence = 0.6): array
    {
        $startTime = microtime(true);

        // 1. Normalisasi dan pembersihan transaksi
        $cleanTransactions = [];
        $allItems = [];

        foreach ($transactions as $rawTx) {
            if (! is_array($rawTx)) {
                continue;
            }
            $cleanTx = array_values(array_unique(array_filter(array_map('trim', $rawTx))));
            if (! empty($cleanTx)) {
                sort($cleanTx);
                $cleanTransactions[] = $cleanTx;
                foreach ($cleanTx as $item) {
                    $allItems[$item] = true;
                }
            }
        }

        $totalTransactions = count($cleanTransactions);

        if ($totalTransactions === 0) {
            return [
                'execution_time_ms' => 0,
                'summary' => [
                    'total_transactions' => 0,
                    'total_unique_items' => 0,
                    'total_frequent_itemsets' => 0,
                    'total_rules_generated' => 0,
                ],
                'frequent_itemsets' => [],
                'association_rules' => [],
            ];
        }

        $minSupportCount = $minSupport * $totalTransactions;

        // 2. Generate Frequent 1-Itemsets (L1)
        $itemCounts = [];
        foreach ($cleanTransactions as $tx) {
            foreach ($tx as $item) {
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1;
            }
        }

        $frequentItemsets = [];
        $itemsetSupportMap = [];

        $allItemSupports = [];
        foreach ($itemCounts as $item => $count) {
            $allItemSupports[] = [
                'item' => $item,
                'count' => $count,
                'support' => round($count / $totalTransactions, 4),
                'support_percent' => round(($count / $totalTransactions) * 100, 2).'%',
                'is_frequent' => $count >= $minSupportCount,
            ];
        }

        // L1
        $currentL = [];
        foreach ($itemCounts as $item => $count) {
            if ($count >= $minSupportCount) {
                $itemset = [$item];
                $currentL[] = $itemset;
                $key = $this->itemsetKey($itemset);
                $itemsetSupportMap[$key] = $count;
                $frequentItemsets[] = [
                    'k' => 1,
                    'items' => $itemset,
                    'count' => $count,
                    'support' => round($count / $totalTransactions, 4),
                    'support_percent' => round(($count / $totalTransactions) * 100, 2).'%',
                ];
            }
        }

        // 3. Iteratif Generate Lk (k = 2, 3, ...)
        $k = 2;
        while (! empty($currentL)) {
            $candidates = $this->generateCandidates($currentL, $k);
            if (empty($candidates)) {
                break;
            }

            // Hitung support untuk setiap candidate
            $candidateCounts = [];
            foreach ($cleanTransactions as $tx) {
                $txSet = array_flip($tx);
                foreach ($candidates as $cand) {
                    $match = true;
                    foreach ($cand as $candItem) {
                        if (! isset($txSet[$candItem])) {
                            $match = false;
                            break;
                        }
                    }
                    if ($match) {
                        $key = $this->itemsetKey($cand);
                        $candidateCounts[$key] = ($candidateCounts[$key] ?? 0) + 1;
                    }
                }
            }

            // Filter dengan minSupport
            $nextL = [];
            foreach ($candidates as $cand) {
                $key = $this->itemsetKey($cand);
                $count = $candidateCounts[$key] ?? 0;
                if ($count >= $minSupportCount) {
                    $nextL[] = $cand;
                    $itemsetSupportMap[$key] = $count;
                    $frequentItemsets[] = [
                        'k' => $k,
                        'items' => $cand,
                        'count' => $count,
                        'support' => round($count / $totalTransactions, 4),
                        'support_percent' => round(($count / $totalTransactions) * 100, 2).'%',
                    ];
                }
            }

            $currentL = $nextL;
            $k++;
        }

        // 4. Generate Association Rules dari Itemsets (k >= 2)
        $rules = [];
        foreach ($frequentItemsets as $itemsetData) {
            if ($itemsetData['k'] < 2) {
                continue;
            }

            $items = $itemsetData['items'];
            $itemsetSupport = $itemsetData['support'];
            $itemsetCount = $itemsetData['count'];

            // Generate semua subset non-empty
            $subsets = $this->getAllSubsets($items);

            foreach ($subsets as $antecedent) {
                $consequent = array_values(array_diff($items, $antecedent));
                if (empty($consequent)) {
                    continue;
                }

                $antKey = $this->itemsetKey($antecedent);
                $antCount = $itemsetSupportMap[$antKey] ?? 0;

                if ($antCount === 0) {
                    continue;
                }

                $antSupport = $antCount / $totalTransactions;
                $confidence = $itemsetCount / $antCount;

                if ($confidence >= $minConfidence) {
                    $consKey = $this->itemsetKey($consequent);
                    $consCount = $itemsetSupportMap[$consKey] ?? 0;
                    $consSupport = $consCount > 0 ? ($consCount / $totalTransactions) : 0;

                    $lift = $consSupport > 0 ? round($confidence / $consSupport, 4) : 0;

                    $rules[] = [
                        'antecedent' => $antecedent,
                        'consequent' => $consequent,
                        'rule_text' => 'Jika membeli ['.implode(', ', $antecedent).'] maka membeli ['.implode(', ', $consequent).']',
                        'count' => $itemsetCount,
                        'support' => round($itemsetSupport, 4),
                        'support_percent' => round($itemsetSupport * 100, 2).'%',
                        'confidence' => round($confidence, 4),
                        'confidence_percent' => round($confidence * 100, 2).'%',
                        'lift_ratio' => $lift,
                        'is_valid_lift' => $lift > 1.0,
                    ];
                }
            }
        }

        // Urutkan rules berdasarkan Confidence tertinggi lalu Lift Ratio
        usort($rules, function ($a, $b) {
            if ($b['confidence'] === $a['confidence']) {
                return $b['lift_ratio'] <=> $a['lift_ratio'];
            }

            return $b['confidence'] <=> $a['confidence'];
        });

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'execution_time_ms' => $executionTime,
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_unique_items' => count($allItems),
                'min_support' => $minSupport,
                'min_confidence' => $minConfidence,
                'total_frequent_itemsets' => count($frequentItemsets),
                'total_rules_generated' => count($rules),
            ],
            'all_items' => $allItemSupports,
            'frequent_itemsets' => $frequentItemsets,
            'association_rules' => $rules,
        ];
    }

    /**
     * Generate calon itemset ukuran k dari itemset ukuran k-1
     */
    protected function generateCandidates(array $prevItemsets, int $k): array
    {
        $candidates = [];
        $n = count($prevItemsets);

        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $merged = array_unique(array_merge($prevItemsets[$i], $prevItemsets[$j]));
                if (count($merged) === $k) {
                    sort($merged);
                    $key = $this->itemsetKey($merged);
                    $candidates[$key] = $merged;
                }
            }
        }

        return array_values($candidates);
    }

    /**
     * Generate semua subset dari sebuah array (kecuali array kosong dan array itu sendiri)
     */
    protected function getAllSubsets(array $items): array
    {
        $subsets = [];
        $total = 1 << count($items);

        for ($i = 1; $i < $total - 1; $i++) {
            $subset = [];
            for ($j = 0; $j < count($items); $j++) {
                if (($i >> $j) & 1) {
                    $subset[] = $items[$j];
                }
            }
            sort($subset);
            $subsets[] = $subset;
        }

        return $subsets;
    }

    protected function itemsetKey(array $itemset): string
    {
        sort($itemset);

        return implode('|||', $itemset);
    }
}
