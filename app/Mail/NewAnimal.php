<?php

namespace App\Mail;

use App\Models\Block;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAnimal extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = Block::where('title', '=', 'email.new-animal')->first();

        $animalsCount = $this->user->animals()->where('verified', 0)->count();

        $email->body = str_replace('{кількість}', $animalsCount, $email->body);
        return $this->view('emails.newAnimal', [
            'body' => $email->body
        ])
            ->subject($email->subject);
    }
}
