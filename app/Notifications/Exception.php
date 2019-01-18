<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class Exception extends Notification
{
    use Queueable;

    private $exception;

    /**
     * Create a new notification instance.
     *
     * @param \Exception $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $exception = $this->exception;

        return (new SlackMessage)
            ->error()
            ->content('*Exception occurred on ' . config('app.env') . ' server* :fire:')
            ->attachment(function ($attachment) use ($exception) {
                $attachment->fields([
                        'Message' => $exception->getMessage(),
                        'User' => (\Auth::user())
                                    ? '#'. \Auth::user()->id . ' - ' . \Auth::user()->name
                                    : 'Undefined',
                        'File' => $exception->getFile(),
                        'Line' => $exception->getLine(),
                    ]);
            });
    }
}
