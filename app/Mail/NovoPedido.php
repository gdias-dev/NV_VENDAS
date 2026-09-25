<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NovoPedido extends Mailable
{
    public function __construct(public Pedido $pedido)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo pedido #'.$this->pedido->id.' - '.$this->pedido->otica?->nome_fantasia,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.novo-pedido',
            with: [
                'pedido' => $this->pedido->load(['otica', 'lente', 'tratamentos']),
            ],
        );
    }
}
