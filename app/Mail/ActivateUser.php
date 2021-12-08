<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateUser extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $attachFile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $attachFile)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->attachFile = $attachFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view("mails.sendActivateUser");
    }
}
