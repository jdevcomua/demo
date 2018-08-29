<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnimalRequestWasAcceped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = \Block::where('title', '=', 'email.request-accepted')->first();
        return $this->view('emails.AnimalRequestWasAccepted', [
            'body' => $email->body
        ])
            ->subject($email->subject);
    }
}
