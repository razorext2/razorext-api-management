<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public const CACHE_KEY = 'site_settings_cache_v1';

    /**
     * Get all settings from cache or DB.
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAllGrouped();

        return array_key_exists($key, $all) && $all[$key] !== null ? $all[$key] : $default;
    }

    /**
     * Set/update a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );

        static::clearCache();
    }

    /**
     * Clear cached settings.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
