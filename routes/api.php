<?php

use App\Http\Controllers\Api\V1\DataMining\AprioriController;
use App\Http\Middleware\ValidateApiKey;
use Illuminate\Support\Facades\Route;

// ─── API Gateway Protected Routes (v1) ───────────────────────────────────────
Route::middleware([ValidateApiKey::class])->prefix('v1')->as('api.v1.')->group(function () {

    // ── Data Mining Engines ──
    Route::prefix('data-mining')->as('data-mining.')->group(function () {
        Route::post('apriori/calculate', [AprioriController::class, 'calculate'])->name('apriori.calculate');
    });

});
