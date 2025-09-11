<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Details to Send with email
     */
    public $details;

    /**
     * Email Subject
     */
    public $subject;

    /**
     * Email Design Layout
     */
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $subject, $template)
    {
        $this->details  = $details;
        $this->subject  = $subject;
        $this->template = $template;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            tags: ['otp', 'verify-email', 'account-activation'],
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: $this->template,
        );
    }
}
