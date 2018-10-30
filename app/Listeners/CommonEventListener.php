<?php

namespace App\Listeners;

use App\Events\CommonEvent;
use App\Models\NotificationTemplate;
use App\Notifications\AlertNotification;
use App\Notifications\MailNotification;
use App\User;

class CommonEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CommonEvent $event
     * @return void
     */
    public function handle($event)
    {
        $notifications = NotificationTemplate::getByEvent(get_class($event));

        foreach ($notifications as $notification) {
            if (!$notification->active) continue;

            switch ($notification->type) {
                case NotificationTemplate::TYPE_EMAIL:
                    $this->sendEmail($event->user, $notification, $event->payload);
                    break;
                case NotificationTemplate::TYPE_ALERT:
                    $this->sendAlert($event->user, $notification, $event->payload);
                    break;
            }
        }
    }

    /**
     * @param User $user
     * @param NotificationTemplate $notification
     * @param array|null $payload
     */
    private function sendEmail(User $user, NotificationTemplate $notification, $payload)
    {
        $user->notify(new MailNotification($notification, $payload));
    }

    /**
     * @param User $user
     * @param NotificationTemplate $notification
     * @param array|null $payload
     */
    private function sendAlert(User $user, NotificationTemplate $notification, $payload)
    {
        $user->notify(new AlertNotification($notification, $payload));
    }
}
