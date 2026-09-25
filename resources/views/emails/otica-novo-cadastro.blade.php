<!DOCTYPE html>
<html lang="pt-BR">
<body style="font-family: Arial, Helvetica, sans-serif; color: #201e1e; line-height: 1.5;">
    <h2 style="color: #12306d; margin-bottom: 4px;">Novo cadastro de ótica</h2>
    <p style="margin-top: 0; color: #64748b;">Aguardando aprovação no painel administrativo.</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <tr><td><strong>Nome fantasia</strong></td><td>{{ $otica->nome_fantasia }}</td></tr>
        <tr><td><strong>Razão social</strong></td><td>{{ $otica->razao_social ?? '-' }}</td></tr>
        <tr><td><strong>CNPJ</strong></td><td>{{ $otica->cnpj ?? '-' }}</td></tr>
        <tr><td><strong>E-mail</strong></td><td>{{ $otica->email }}</td></tr>
        <tr><td><strong>Telefone</strong></td><td>{{ $otica->telefone ?? '-' }}</td></tr>
        <tr><td><strong>Endereço</strong></td><td>{{ $otica->endereco ?? '-' }}</td></tr>
    </table>

    <p style="margin-top: 24px;">Acesse o painel administrativo, em <strong>Óticas</strong>, para revisar e aprovar.</p>
</body>
</html>
