<?php

/** Goal: Show unread announcements as popup for logged-in users, Caller: layoutsDash/app.blade.php, Deps: Announcement, AnnouncementRead */

namespace App\Livewire\Utils;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnnouncementContainer extends Component
{
    public ?Announcement $announcement = null;

    public bool $hasRead = false;

    public bool $showModal = false;

    public ?int $announcementId = null;

    public function mount(): void
    {
        $this->loadNextAnnouncement();
    }

    public function loadNextAnnouncement(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->announcement = null;
            $this->announcementId = null;
            $this->showModal = false;

            return;
        }

        $this->announcement = Announcement::unreadForUser($user)->first();
        $this->announcementId = $this->announcement?->id;
        $this->hasRead = false;
        $this->showModal = (bool) $this->announcement;
    }

    public function markAsRead(): void
    {
        if ($this->hasRead && $this->announcement) {
            AnnouncementRead::create([
                'announcement_id' => $this->announcement->id,
                'user_id' => Auth::id(),
                'read_at' => now(),
            ]);

            $this->loadNextAnnouncement();
            if (! $this->announcement) {
                $this->dispatch('announcement-closed');
            }
        }
    }

    public function render(): View
    {
        return view('livewire.utils.announcement-container');
    }
}
