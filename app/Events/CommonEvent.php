<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

abstract class CommonEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $payload;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param array|null $payload
     */
    public function __construct($user, $payload = null)
    {
        $this->user = $user;
        $this->payload = $payload;
    }
}
