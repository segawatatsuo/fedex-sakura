<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $mail_subject, $content )
    {
        $this->name = $name;
        $this->email = $email;
        $this->mail_subject = $mail_subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
            ->subject($this->mail_subject)
            ->view('mail.testMail')
            ->with([
                'name' => $this->name,
                'content' => $this->content,
            ]);
    }
}
