<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterPersoenlich extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $empfaenger;

    /**
     * Create a new message instance.
     */
    public function __construct($empfaenger)
    {
        //

        $this->empfaenger = $empfaenger;
        
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ihr täglicher Newsletter',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.newsletter_persoenlich',
            text: 'mail.newsletter_persoenlich_plain'
           
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return ['Reise.pdf'];
    }
}
