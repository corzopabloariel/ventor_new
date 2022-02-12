<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $response = $this->from(config("app.mail.FROM_ADDRESS"))
                    ->view('mails.demo_plain')
                    ->subject($this->demo['subject'])
                    ->with($this->demo);
        if (isset($this->demo['reply'])) {

            $response = $response->replyTo($this->demo['reply']['email'], $this->demo['reply']['name']);

        }
        if (isset($this->demo['attach'])) {

            $response = $response
                ->attach($this->demo['attach']['file'], [
                        'as' => $this->demo['attach']['name'],
                        'mime' => $this->demo['attach']['mime'],
                ]);

        }
        return $response;
    }
}
