<?php

namespace App\Mail;

use App\Models\Block;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAnimal extends Mailable
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
        $email = Block::where('title', '=', 'email.new-animal')->first();
        return $this->view('emails.newAnimal')
            ->subject($email->subject);
    }
}
