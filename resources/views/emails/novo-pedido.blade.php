<!DOCTYPE html>
<html lang="pt-BR">
<body style="font-family: Arial, Helvetica, sans-serif; color: #201e1e; line-height: 1.5;">
    <h2 style="color: #12306d; margin-bottom: 4px;">Novo pedido #{{ $pedido->id }}</h2>
    <p style="margin-top: 0; color: #64748b;">Ótica: {{ $pedido->otica?->nome_fantasia }}</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <tr><td><strong>Cliente</strong></td><td>{{ $pedido->cliente_nome }}</td></tr>
        <tr><td><strong>Telefone</strong></td><td>{{ $pedido->cliente_telefone ?? '-' }}</td></tr>
        <tr><td><strong>Lente</strong></td><td>{{ $pedido->lente_nome ?? '-' }}</td></tr>
        <tr><td><strong>OD</strong></td><td>Esf. {{ $pedido->od_esferico ?? '-' }} / Cil. {{ $pedido->od_cilindrico ?? '-' }} / Eixo {{ $pedido->od_eixo ?? '-' }} / Ad. {{ $pedido->od_adicao ?? '-' }}</td></tr>
        <tr><td><strong>OE</strong></td><td>Esf. {{ $pedido->oe_esferico ?? '-' }} / Cil. {{ $pedido->oe_cilindrico ?? '-' }} / Eixo {{ $pedido->oe_eixo ?? '-' }} / Ad. {{ $pedido->oe_adicao ?? '-' }}</td></tr>
        <tr><td><strong>Tratamentos</strong></td><td>{{ $pedido->tratamentos->pluck('tratamento_nome')->join(', ') ?: '-' }}</td></tr>
        <tr><td><strong>Montagem</strong></td><td>{{ $pedido->com_montagem ? 'Sim' : 'Não' }}{{ $pedido->montagem_observacoes ? ' - '.$pedido->montagem_observacoes : '' }}</td></tr>
        <tr><td><strong>Valor total</strong></td><td>R$ {{ number_format((float) $pedido->preco_total, 2, ',', '.') }}</td></tr>
    </table>

    <p style="margin-top: 24px;">Acesse o painel administrativo, em <strong>Pedidos</strong>, para ver os detalhes e atualizar o status.</p>
</body>
</html>
