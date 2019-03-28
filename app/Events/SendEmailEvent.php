<?php

namespace App\Events;


abstract class SendEmailEvent
{
    protected $email;
    protected $subject;
    protected $body;

    /**
     * Create a new event instance.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

}
