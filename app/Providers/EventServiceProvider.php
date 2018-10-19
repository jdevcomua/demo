<?php

namespace App\Providers;

use App;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\AnimalRequestAccepted' => [
            'App\Listeners\CommonEventListener',
        ],
        'App\Events\AnimalRequestDeclined' => [
            'App\Listeners\CommonEventListener',
        ],
        'App\Events\AnimalAdded' => [
            'App\Listeners\CommonEventListener',
        ],
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
