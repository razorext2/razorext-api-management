<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequestLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'api_request_logs';

    protected $fillable = [
        'api_client_id',
        'endpoint',
        'method',
        'status_code',
        'ip_address',
        'user_agent',
        'execution_time_ms',
        'error_message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'status_code' => 'integer',
            'execution_time_ms' => 'float',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}
