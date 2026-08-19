<?php

namespace App\Livewire\Utils;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadUnreadCount();
    }

    #[On('notification-received')]
    public function loadUnreadCount()
    {
        $user = auth()->user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications->count();
        }
    }

    #[Computed]
    public function notifications()
    {
        $user = auth()->user();

        return $user ? $user->unreadNotifications()->take(10)->get() : collect();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            $this->loadUnreadCount();
            $this->dispatch('notification-updated');
        }
    }

    public function render()
    {
        return view('livewire.utils.notification-dropdown');
    }
}
