<?php

namespace App\Listeners;

use App\Events\SendEmailEvent;
use App\Mail\CustomMail;
use Carbon\Carbon;

class SendEmailEventListener
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(SendEmailEvent $event)
    {
        $message = (new CustomMail($event->getSubject(), $event->getBody()))
            ->onQueue('default')
            ->delay(Carbon::now()->addSecond());

        \Mail::to($event->getEmail())
            ->queue($message);
    }
}
