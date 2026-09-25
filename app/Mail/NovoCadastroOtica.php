<?php

namespace App\Mail;

use App\Models\Otica;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NovoCadastroOtica extends Mailable
{
    public function __construct(public Otica $otica)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo cadastro de ótica pendente: '.$this->otica->nome_fantasia,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otica-novo-cadastro',
        );
    }
}
