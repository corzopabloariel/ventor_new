<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $title, $body, $replyToPerson = null)
    {
        $this->subject = $subject;
        $this->title = $title;
        $this->body = $body;
        $this->replyToPerson = $replyToPerson;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this;
        if (!empty($this->replyToPerson))
            $message = $message->replyTo($this->replyToPerson["email"], $this->replyToPerson["name"]);
        $message = $message->subject($this->subject);
        $message = $message->view('mail.base')->with([
                'subject' => $this->subject,
                'title' => $this->title,
                'body' => $this->body
            ]);
        return $message;
    }
}
