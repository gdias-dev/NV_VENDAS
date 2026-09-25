<?php

namespace App\Observers;

use App\Mail\PedidoStatusAtualizado;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PedidoObserver
{
    /**
     * Avisa a ótica por e-mail sempre que o status do pedido muda (seja lá
     * quem tenha feito a mudança no painel administrativo). Não avisa na
     * criação do pedido em si — quem recebe esse aviso é o laboratório
     * (App\Mail\NovoPedido, disparado pelo próprio assistente de pedido).
     */
    public function updated(Pedido $pedido): void
    {
        if (! $pedido->wasChanged('status')) {
            return;
        }

        $email = $pedido->otica?->email;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new PedidoStatusAtualizado($pedido));
        } catch (Throwable $e) {
            Log::error('Falha ao avisar a ótica sobre a mudança de status do pedido: '.$e->getMessage(), [
                'pedido_id' => $pedido->id,
            ]);
        }
    }
}
