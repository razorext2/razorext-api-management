<?php

/** Goal: Announcement model for company-wide announcements, Caller: AnnouncementTable, AnnouncementContainer, Deps: AnnouncementRead */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'description',
        'status',
        'file_path',
        'target_type',
        'target_roles',
        'target_users',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'target_users' => 'array',
    ];

    public function reads(): HasMany
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    /**
     * Scope a query to only include unread announcements for a given user.
     */
    public function scopeUnreadForUser($query, ?User $user)
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        $roles = $user->roles->pluck('id')->toArray();
        $userId = $user->id;

        return $query->where('status', 1)
            ->whereDoesntHave('reads', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where(function ($q) use ($roles, $userId) {
                $q->where('target_type', 'all')
                    ->orWhere(function ($sq) use ($roles) {
                        $sq->where('target_type', 'role');
                        if (empty($roles)) {
                            $sq->whereRaw('1 = 0');
                        } else {
                            $sq->where(function ($sq2) use ($roles) {
                                foreach ($roles as $role) {
                                    $sq2->orWhereJsonContains('target_roles', (int) $role)
                                        ->orWhereJsonContains('target_roles', (string) $role);
                                }
                            });
                        }
                    })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('target_type', 'user')
                            ->where(function ($sub) use ($userId) {
                                $sub->whereJsonContains('target_users', (int) $userId)
                                    ->orWhereJsonContains('target_users', (string) $userId);
                            });
                    });
            });
    }

    /**
     * Check if the given user has any unread announcements.
     */
    public static function hasUnreadForUser(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return once(fn () => self::unreadForUser($user)->exists());
    }
}
