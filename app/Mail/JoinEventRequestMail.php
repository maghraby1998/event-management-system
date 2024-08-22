<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JoinEventRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eventOwner;
    public $eventRequester;
    public $eventName;

    /**
     * Create a new message instance.
     */
    public function __construct($eventOwner, $eventRequester, $eventName)
    {
        $this->eventOwner = $eventOwner;
        $this->eventRequester = $eventRequester;
        $this->eventName = $eventName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Join Event Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'JoinEventRequestView',
            with: [
                'eventOwner' => $this->eventOwner,
                'eventRequester' => $this->eventRequester,
                'eventName' => $this->eventName,
            ]
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
