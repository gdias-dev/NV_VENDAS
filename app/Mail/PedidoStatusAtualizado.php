<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PedidoStatusAtualizado extends Mailable
{
    public function __construct(public Pedido $pedido)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido #'.$this->pedido->id.' - '.$this->pedido->statusLabel(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-status-atualizado',
            with: [
                'pedido' => $this->pedido,
            ],
        );
    }
}
