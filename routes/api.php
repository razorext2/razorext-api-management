<?php

use App\Http\Controllers\Api\ApiAnnouncementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:high'])->group(function () {
    // Announcement API
    Route::patch('announcement-api/{id}/state', [ApiAnnouncementController::class, 'changeState'])->name('announcement-api.change-state');
    Route::apiResource('announcement-api', ApiAnnouncementController::class)->only(['store', 'show', 'update', 'destroy']);
});
