<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    use HasFactory;

    protected $table = 'api_clients';

    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'secret_key',
        'description',
        'rate_limit_per_minute',
        'is_active',
        'allowed_ips',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allowed_ips' => 'array',
            'last_used_at' => 'datetime',
            'rate_limit_per_minute' => 'integer',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApiRequestLog::class, 'api_client_id');
    }

    public static function generateKey(string $prefix = 'apm_live_'): string
    {
        return $prefix.Str::random(40);
    }

    public static function generateSecret(): string
    {
        return 'sec_'.Str::random(48);
    }
}
