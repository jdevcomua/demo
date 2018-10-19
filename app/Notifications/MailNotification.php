<?php

namespace App\Notifications;

use App\Mail\CustomMail;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param NotificationTemplate $notification
     */
    public function __construct(NotificationTemplate $notification)
    {
        $this->notification = $notification;
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
        return (new CustomMail($notifiable, 'block-name'))
            ->subject('')
            ->to($notifiable->primaryEmail);
    }
}
