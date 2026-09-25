<!DOCTYPE html>
<html lang="pt-BR">
<body style="font-family: Arial, Helvetica, sans-serif; color: #201e1e; line-height: 1.5;">
    <h2 style="color: #12306d; margin-bottom: 4px;">Novo contato pelo site</h2>
    <p style="margin-top: 0; color: #64748b;">{{ $assuntoTexto }}</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <tr><td><strong>Nome</strong></td><td>{{ $dados['nome'] }}</td></tr>
        <tr><td><strong>Ótica</strong></td><td>{{ $dados['otica'] ?? '-' }}</td></tr>
        <tr><td><strong>E-mail</strong></td><td>{{ $dados['email'] }}</td></tr>
        <tr><td><strong>Telefone</strong></td><td>{{ $dados['telefone'] ?? '-' }}</td></tr>
    </table>

    <h3 style="color: #12306d;">Mensagem</h3>
    <p style="white-space: pre-line;">{{ $dados['mensagem'] }}</p>

    <hr style="border: none; border-top: 1px solid #e2e8f0;">
    <p style="font-size: 12px; color: #64748b;">Enviado pelo formulário de contato do site. Responda este e-mail para falar diretamente com o contato.</p>
</body>
</html>
