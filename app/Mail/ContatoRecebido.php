<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContatoRecebido extends Mailable
{
    public const ASSUNTOS = [
        'cadastro' => 'Quero cadastrar minha ótica',
        'duvida' => 'Dúvida técnica / receita',
        'orcamento' => 'Orçamento',
        'outro' => 'Outro assunto',
    ];

    public function __construct(public array $dados)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->dados['email'], $this->dados['nome'])],
            subject: 'Contato pelo site: '.(self::ASSUNTOS[$this->dados['assunto']] ?? 'Mensagem').' - '.$this->dados['nome'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contato',
            with: ['assuntoTexto' => self::ASSUNTOS[$this->dados['assunto']] ?? 'Mensagem'],
        );
    }
}
