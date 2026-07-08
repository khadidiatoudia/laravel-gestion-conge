<?php

namespace App\Mail;

use App\Models\Conge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CongeRefuse extends Mailable {
    use Queueable, SerializesModels;

    public Conge $conge;

    public function __construct(Conge $conge) {
        $this->conge = $conge;
    }

    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Votre demande de congé a été refusée',
        );
    }

    public function content(): Content {
        return new Content(
            view: 'emails.conge-refuse',
            with: [
                'agent' => $this->conge->agent,
                'conge' => $this->conge,
            ]
        );
    }
}
