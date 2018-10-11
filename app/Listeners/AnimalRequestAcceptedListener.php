<?php

namespace App\Listeners;

use App\Events\AnimalRequestAccepted;
use App\Mail\AnimalRequestWasAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnimalRequestAcceptedListener
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
     * @param  AnimalRequestAccepted  $event
     * @return void
     */
    public function handle(AnimalRequestAccepted $event)
    {
        if ($event->user->primaryEmail) {
            \Mail::to($event->user->primaryEmail)
                ->send(new AnimalRequestWasAccepted());
        }
    }
}
