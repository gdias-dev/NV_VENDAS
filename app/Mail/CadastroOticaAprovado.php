<?php

namespace App\Mail;

use App\Models\Otica;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CadastroOticaAprovado extends Mailable
{
    public function __construct(public Otica $otica)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu cadastro na Nova Varonil foi aprovado',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otica-aprovada',
        );
    }
}
