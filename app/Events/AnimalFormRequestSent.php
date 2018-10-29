<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AnimalFormRequestSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public static $display_name = 'Користувач надіслав форму для пошуку тварини';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
