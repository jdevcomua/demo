<?php


namespace App\Notifications;


use Illuminate\Notifications\Notifiable;

class EmailNotifiable
{
    use Notifiable;

    public $email;
}