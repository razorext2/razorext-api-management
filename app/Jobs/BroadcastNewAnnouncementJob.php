<?php

/** Goal: Broadcast notifikasi announcement ke semua user, Caller: AnnouncementController, Deps: NewAnnouncementEvent, User */

namespace App\Jobs;

use App\Events\NewAnnouncementEvent;
use App\Helpers\ErrorLogger;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BroadcastNewAnnouncementJob implements ShouldQueue
{
    use Queueable;

    /** @var int Jumlah retry jika job gagal */
    public int $tries = 3;

    /** @var int Timeout per attempt dalam detik */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly mixed $announcement) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::select('id')->get();

        foreach ($users as $user) {
            broadcast(new NewAnnouncementEvent($user->id, $this->announcement));
        }
    }

    /**
     * Handle a job failure — dipanggil setelah semua retry habis.
     */
    public function failed(\Throwable $exception): void
    {
        ErrorLogger::log($exception, 'BroadcastNewAnnouncementJob permanently failed', [
            'announcement' => $this->announcement,
        ]);
    }

    /**
     * Hitung backoff delay antar retry (exponential).
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [10, 30, 60];
    }
}
