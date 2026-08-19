<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting()
    {
        // Default rate limiter for the 'api' routes, allowing 60 requests per minute.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
        // Custom rate limiter for 'test' route, with different limits per second and per minute.
        RateLimiter::for('high', function (Request $request) {
            return [
                Limit::perSecond(1)->by($request->ip()), // Limit to 1 requests per second by IP address.
                Limit::perMinute(10)->by($request->ip()), // Limit to 10 requests per minute by IP address.
            ];
        });

        RateLimiter::for('medium', function (Request $request) {
            return [
                Limit::perSecond(10)->by($request->ip()),
                Limit::perMinute(30)->by($request->ip()),
            ];
        });

        RateLimiter::for('low', function (Request $request) {
            return [
                Limit::perSecond(20)->by($request->ip()),
                Limit::perMinute(60)->by($request->ip()),
            ];
        });
    }
}
