<?php

namespace App\Listeners;

use App\Events\AnimalAdded;
use App\Models\NotificationTemplate;
use App\Notifications\AlertNotification;
use App\Notifications\MailNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param $event
     * @return void
     */
    public function handle($event)
    {
        $notifications = NotificationTemplate::getByEvent(get_class($event));

        foreach ($notifications as $notification) {
            if (!$notification->active) continue;

            switch ($notification->type) {
                case NotificationTemplate::TYPE_EMAIL:
                    $this->sendEmail($event->user, $notification);
                    break;
                case NotificationTemplate::TYPE_ALERT:
                    $this->sendAlert($event->user, $notification);
                    break;
            }
        }
    }

    /**
     * @param User $user
     * @param NotificationTemplate $notification
     */
    private function sendEmail(User $user, NotificationTemplate $notification)
    {
        $user->notify(new MailNotification($notification));
    }

    /**
     * @param User $user
     * @param NotificationTemplate $notification
     */
    private function sendAlert(User $user, NotificationTemplate $notification)
    {
        $user->notify(new AlertNotification($notification));
    }
}
