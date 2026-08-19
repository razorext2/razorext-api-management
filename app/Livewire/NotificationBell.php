<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    #[On('notification-updated')]
    #[On('notification-received')]
    public function render()
    {
        $notification = auth()->user()->unreadNotifications;

        return view('livewire.notification-bell', [
            'notification' => $notification,
        ]);
    }
}
