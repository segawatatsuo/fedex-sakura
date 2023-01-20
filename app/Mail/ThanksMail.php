<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThanksMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $content;


    public function __construct($content)
    {
        $this->content = $content;
        //コントローラーから送られた$contentをセット。View側では{{ $content['name'] }}
    }

    public function build()
    {
        return $this->from('export@ccmedico.com')
        ->subject('C.C.Medico Thank you for your order.')
        ->view('emails.thanksMail')->with(['content' => $this->content]); 
    }
}
