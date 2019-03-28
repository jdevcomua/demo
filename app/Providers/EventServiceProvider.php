<?php

namespace App\Providers;

use App;
use App\Listeners\AnimalAddedEventListener;
use App\Listeners\CommonEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\AnimalRequestAccepted'  =>  [CommonEventListener::class],
        'App\Events\AnimalRequestDeclined'  =>  [CommonEventListener::class],
        'App\Events\AnimalAdded'            =>  [CommonEventListener::class, AnimalAddedEventListener::class],
        'App\Events\AnimalBadgeRequestSent' =>  [CommonEventListener::class],
        'App\Events\AnimalFormRequestSent'  =>  [CommonEventListener::class],
        'App\Events\AnimalFoundCreated'  =>  ['App\Listeners\SendEmailEventListener'],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $events = [];
        foreach (array_keys($this->listen) as $event) {
            if (!class_exists($event)) continue;

            $events[$event::$display_name] = $event;
        }

        App::singleton('rha_events', function() use($events) {
            return $events;
        });
    }
}
