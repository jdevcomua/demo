<?php

namespace App\Listeners;

use App\Events\AnimalRequestDeclined;
use App\Mail\AnimalRequestWasDeclined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnimalRequestDeclinedListener
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
     * @param  AnimalRequestDeclined  $event
     * @return void
     */
    public function handle(AnimalRequestDeclined $event)
    {
        if ($event->user->primaryEmail) {
            \Mail::to($event->user->primaryEmail)
                ->send(new AnimalRequestWasDeclined());
        }
    }
}
