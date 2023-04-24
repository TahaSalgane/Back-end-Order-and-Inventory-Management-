<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ForgetMail extends Mailable
{
    use Queueable, SerializesModels;
    public $token,$name;
    /**
     * Create a new message instance.
     */
    public function __construct($token,$name)
    {
        $this->token=$token;
        $this->name=$name;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('skalopro@gmail.com'),
            subject: 'Forget Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $token = $this->token;
        $name = $this->name;

        return new Content(
            view: 'mail.forget',
            with: [
                'data' => $token,
                'name' => $name
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
