<?php

/** Goal: Track which user has read which announcement, Caller: AnnouncementContainer, Deps: Announcement, User */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementRead extends Model
{
    use HasFactory;

    protected $table = 'announcement_reads';

    protected $fillable = [
        'announcement_id',
        'user_id',
        'read_at',
    ];

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
