<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

abstract class CommonEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifiable;
    public $payload;

    /**
     * Create a new event instance.
     *
     * @param $notifiable
     * @param array|null $payload
     */
    public function __construct($notifiable, $payload = null)
    {
        $this->notifiable = $notifiable;
        $this->payload = $payload;
    }
}
