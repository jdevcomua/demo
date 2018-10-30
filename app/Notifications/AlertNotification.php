<?php

namespace App\Notifications;

use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $notification;
    private $payload;

    /**
     * Create a new notification instance.
     *
     * @param NotificationTemplate $notification
     * @param array|null $payload
     */
    public function __construct(NotificationTemplate $notification, $payload)
    {
        $this->notification = $notification;
        $this->payload = $payload;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notification' => $this->notification,
            'payload' => $this->payload,
        ];
    }
}
