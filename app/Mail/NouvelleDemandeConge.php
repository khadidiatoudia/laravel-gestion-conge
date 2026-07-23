<?php

namespace App\Mail;

use App\Models\Conge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelleDemandeConge extends Mailable {
    use Queueable, SerializesModels;

    public Conge $conge;

    public function __construct(Conge $conge) {
        $this->conge = $conge;
    }

    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Nouvelle demande de congé à valider — ' . $this->conge->agent->nom_complet,
        );
    }

    public function content(): Content {
        return new Content(
            view: 'emails.conge-nouvelle-demande',
            with: [
                'agent' => $this->conge->agent,
                'conge' => $this->conge,
            ]
        );
    }
}
