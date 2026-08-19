<?php

/** Goal: Livewire Full Page component for Notifications Center, Caller: routes/web.php (notifications.index), Livewire: Yes */

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsIndex extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        $notification = auth()->user()?->unreadNotifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()?->unreadNotifications->markAsRead();
        session()->flash('status', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function render(): View
    {
        $notifications = auth()->user()
            ?->notifications()
            ->reorder()
            ->orderByRaw('read_at IS NOT NULL ASC')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.notifications-index', [
            'notifications' => $notifications,
        ]);
    }
}
