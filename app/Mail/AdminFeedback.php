<?php

namespace App\Mail;

class AdminFeedback extends BaseMailable
{

    public $user;
    public $message;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.account.feedback')->subject('Account Help/Feedback From ' . $this->user->name);
    }
}
