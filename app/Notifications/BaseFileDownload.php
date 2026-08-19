<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BaseFileDownload extends Notification implements ShouldQueue
{
    use Queueable;

    protected ?string $route;

    protected ?array $parameters;

    protected ?string $message;

    protected ?string $label;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $route, array $parameters, string $message, string $label)
    {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->message = $message;
        $this->label = $label;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'button' => [
                'url' => route($this->route, $this->parameters),
                'label' => $this->label,
            ],
            'created_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('PT. Indodacin Presisi Utama')
            ->body($this->message)
            ->icon(asset('images/brand/logo.ico'))
            ->badge(asset('images/brand/logo.ico'))
            ->action($this->label, route($this->route, $this->parameters))
            ->tag('Indodacin')
            ->data(['url' => route($this->route, $this->parameters)]);
    }
}
