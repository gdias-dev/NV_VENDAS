<!DOCTYPE html>
<html lang="pt-BR">
<body style="font-family: Arial, Helvetica, sans-serif; color: #201e1e; line-height: 1.5;">
    <h2 style="color: #12306d; margin-bottom: 4px;">Cadastro aprovado!</h2>
    <p>Olá, {{ $otica->nome_fantasia }}.</p>
    <p>Seu cadastro na Nova Varonil foi aprovado. Você já pode entrar no portal com o e-mail e a senha que cadastrou:</p>
    <p style="margin: 20px 0;">
        <a href="{{ route('area-oticas') }}" style="background: #12306d; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: bold;">
            Acessar o portal
        </a>
    </p>
    <p style="font-size: 13px; color: #64748b;">Se você não reconhece este cadastro, ignore este e-mail ou fale com o laboratório.</p>
</body>
</html>
