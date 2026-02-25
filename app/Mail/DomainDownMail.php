<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class DomainDownMail extends Mailable
{
    public function __construct(
        public readonly string $url
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔴 Domain is down: ' . $this->url,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.domain-down',
        );
    }
}
