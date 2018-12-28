<?php

namespace App\Listeners;

use App\Events\AnimalAdded;
use App\External\AnimalAddedOrder;
use App\External\OrderService;

class AnimalAddedEventListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AnimalAdded $event
     * @return void
     */
    public function handle($event)
    {
        $order = new AnimalAddedOrder($event->user, $event->animal);
        $orderService = new OrderService($order);
        $orderService->sendData();
    }


}
