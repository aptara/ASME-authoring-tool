<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        $fromEmail = $this->data['fromEmail'];
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $subject = $this->data['subject'];

        return $this->from($fromEmail)
            ->subject($subject)
            ->markdown('mail.send-notification', ['data' => $this->data]);
    }
}
