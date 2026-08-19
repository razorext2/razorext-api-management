<?php

namespace App\Http\Controllers\Api\V1\DataMining;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DataMining\AprioriRequest;
use App\Services\DataMining\AprioriService;
use Illuminate\Http\JsonResponse;

class AprioriController extends Controller
{
    public function __construct(
        protected AprioriService $aprioriService
    ) {}

    /**
     * Menjalankan kalkulasi algoritma Apriori.
     */
    public function calculate(AprioriRequest $request): JsonResponse
    {
        $transactions = $request->validated('transactions');
        $minSupport = (float) ($request->validated('min_support') ?? 0.2);
        $minConfidence = (float) ($request->validated('min_confidence') ?? 0.6);

        $result = $this->aprioriService->calculate($transactions, $minSupport, $minConfidence);

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Kalkulasi Apriori berhasil diselesaikan.',
            'execution_time_ms' => $result['execution_time_ms'],
            'summary' => $result['summary'],
            'data' => [
                'all_items' => $result['all_items'] ?? [],
                'frequent_itemsets' => $result['frequent_itemsets'],
                'association_rules' => $result['association_rules'],
            ],
        ], 200);
    }
}
