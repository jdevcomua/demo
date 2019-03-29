<?php

namespace App\Notifications;

use App\Mail\CustomMail;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailNotification extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Mail\Mailable
     */
    public function toMail($notifiable)
    {
        $body = $this->notification->fillTextPlaceholders($notifiable, $this->payload);

        $email = $notifiable->email ?? $notifiable->primaryEmail;

        return (new CustomMail($this->notification->subject, $body))->to($email);
    }
}
