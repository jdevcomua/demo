<?php

namespace App\Mail;

use App\User;
use Block;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $blockName;
    public $params;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $blockName
     */
    public function __construct(User $user, string $blockName, array $params)
    {
        $this->user = $user;
        $this->blockName = $blockName;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @throws \Exception
     */
    public function build()
    {
        $block = new \App\Models\Block();
        $block = $block->where('title', '=', $this->blockName)->first();

        if (!$block) throw new \Exception("Block '$this->blockName' not found");

//        $animalsCount = $this->user->animals()->where('verified', 0)->count();
//
//        $email->body = str_replace('{кількість}', $animalsCount, $email->body);
//        return $this->view('emails.newAnimal', [
//            'body' => $email->body
//        ])
//            ->subject($email->subject);

        return $this->view('view.name');
    }
}
