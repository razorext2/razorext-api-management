<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Get or set setting values globally.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return Setting::getAllGrouped();
        }

        return Setting::get($key, $default);
    }
}
