<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAnnouncementEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user_id;

    protected $announcement;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $announcement)
    {
        $this->user_id = $user_id;
        $this->announcement = $announcement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("announcements.$this->user_id"),
        ];
    }

    public function broadcastAs()
    {
        return 'newAnnouncement';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'description' => $this->announcement->description,
            'created_at' => Carbon::now()->locale('id')->isoFormat('DD MMM YYYY HH:mm:ss'),
        ];
    }
}
