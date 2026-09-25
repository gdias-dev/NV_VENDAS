<!DOCTYPE html>
<html lang="pt-BR">
<body style="font-family: Arial, Helvetica, sans-serif; color: #201e1e; line-height: 1.5;">
    <h2 style="color: #12306d; margin-bottom: 4px;">Atualização do pedido #{{ $pedido->id }}</h2>
    <p style="margin-top: 0; color: #64748b;">Cliente: {{ $pedido->cliente_nome }}</p>

    <p>
        O status do seu pedido mudou para:
        <strong style="color: #12306d;">{{ $pedido->statusLabel() }}</strong>
    </p>

    @if ($pedido->status === \App\Models\Pedido::STATUS_ENTREGUE)
        <p>O pedido foi entregue. Obrigado pela confiança!</p>
    @elseif ($pedido->status === \App\Models\Pedido::STATUS_CANCELADO)
        <p>Este pedido foi cancelado. Se tiver dúvidas, fale com o laboratório.</p>
    @endif

    <p style="margin: 20px 0;">
        <a href="{{ route('portal.pedidos.show', $pedido) }}" style="background: #12306d; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: bold;">
            Ver o pedido
        </a>
    </p>

    <p style="font-size: 13px; color: #64748b;">Nova Varonil - Laboratório Óptico</p>
</body>
</html>
