<?php

namespace App\Events;

class AnimalAdded extends CommonEvent
{
    public $animal;

    public function __construct($user, $animal, ?array $payload = null)
    {
        parent::__construct($user, $payload);
        $this->animal = $animal;
    }

    public static $display_name = 'Додано нову тварину';
}
