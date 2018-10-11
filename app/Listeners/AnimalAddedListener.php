<?php

namespace App\Listeners;

use App\Events\AnimalAdded;
use App\Mail\NewAnimal;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnimalAddedListener
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
     * @param  AnimalAdded  $event
     * @return void
     */
    public function handle(AnimalAdded $event)
    {
        if ($event->user->primaryEmail) {
            $event->user->notify(new \App\Notifications\AnimalAdded());
        }
    }
}
