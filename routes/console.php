<?php

/** Goal: Central console route scheduler */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Hapus notifikasi > 7 hari, sekali sehari
Schedule::call(function () {
    DB::table('notifications')
        ->where('created_at', '<', now()->subWeek())
        ->delete();
})
    ->timezone('Asia/Jakarta')
    ->dailyAt('00:30')
    ->name('Purge notifications >7 hari')
    ->onOneServer()
    ->withoutOverlapping()
    ->evenInMaintenanceMode();

// Hapus log > 7 hari, sekali sehari
Schedule::call(function () {
    DB::table('log_histories')
        ->where('created_at', '<', now()->subWeek())
        ->delete();
})
    ->timezone('Asia/Jakarta')
    ->dailyAt('00:50')
    ->name('Purge logs >7 hari')
    ->onOneServer()
    ->withoutOverlapping()
    ->evenInMaintenanceMode();
