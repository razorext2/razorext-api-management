<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:high'])->group(function () {
    // API Routes
});

