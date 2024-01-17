<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Quote2 extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $number;

    public function __construct($number)
    {
        //
        $this->number = $number;
    }

    public function build()
    {
    
        return $this->view("mail.quote",['numner' => $this->number])
        ->text('mail.quote_plain');
    }
}
