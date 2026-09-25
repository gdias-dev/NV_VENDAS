# Nova Varonil - Site e Portal de Pedidos

Etapa 1: site público. Etapa 2: banco de dados e painel administrativo (Filament).
Etapa 3: cadastro público e login das próprias óticas, com aprovação.
Etapa 4: assistente de pedido, com cálculo automático de preço.
Etapa 5: aviso por e-mail de mudança de status e PDF da ordem de serviço.

## 1. Atualizar o projeto

Baixe este zip **por cima da pasta do projeto** (ele não inclui `vendor/` nem `.env`).

Esta etapa adiciona **um pacote novo** (para gerar o PDF), então desta vez precisa
rodar o `composer update`:

```bash
php C:\composer\composer.phar update
```

(ou só `composer update`, se você já tiver colocado o Composer no PATH). Não tem
migration nova nesta etapa.

## 2. O que mudou

- **E-mail de status**: toda vez que o status de um pedido muda (você muda no painel
  administrativo, em Pedidos → "Mudar status"), o sistema tenta avisar a ótica por
  e-mail automaticamente — mesmo jeito best-effort das outras etapas (se o envio
  falhar, a mudança de status é salva do mesmo jeito).
- **PDF da ordem de serviço**: cada pedido agora tem um botão para gerar um PDF com
  os dados completos — ótica, cliente, receita, lente, tratamentos, montagem e
  valores. Esse botão aparece:
  - No portal da ótica, na tela de detalhe do pedido e na lista "Meus pedidos"
    (e também na tela de sucesso, assim que o pedido é confirmado).
  - No painel administrativo, em Pedidos, tanto na lista quanto na tela de detalhe.
  - O PDF abre numa aba nova do navegador (pode salvar ou imprimir direto de lá).

## 3. Testar localmente

```bash
php artisan serve
```

1. No painel administrativo (`/admin` → **Pedidos**), abra um pedido existente (ou
   crie um novo pelo portal da ótica, como na Etapa 4) e clique em **"Mudar status"**.
2. Veja em `storage/logs/laravel-AAAA-MM-DD.log` o e-mail de aviso da mudança de
   status (com `MAIL_MAILER=log`, que é o padrão local).
3. Clique no botão **"PDF"** (na lista de Pedidos ou na tela de detalhe) e confira
   se o PDF abre certinho, com os dados do pedido.
4. Faça o mesmo teste pelo lado da ótica: entre em `/area-das-oticas`, vá em
   **"Meus pedidos"** e clique em **"PDF"** num pedido.

Se o `composer update` reclamar de alguma extensão do PHP faltando (como aconteceu
com o `intl`, na Etapa 2), o jeito de resolver é o mesmo: abrir o `php.ini` (descubra
o caminho com `php --ini`) e descomentar a extensão pedida.

## 4. Publicar na KingHost

Os passos são os mesmos de antes (FileZilla, apontar para `public`, etc.). Como esta
etapa tem um pacote novo, depois de subir os arquivos é preciso atualizar as
dependências no servidor também — se você não tiver acesso SSH na KingHost para
rodar `composer update` lá, o caminho mais simples é: rodar `composer update` aqui
no seu computador (o comando do passo 1) e depois subir a pasta `vendor/` inteira,
atualizada, para a KingHost via FileZilla também (ela é grande, pode demorar um
pouco para subir).

## 5. Estrutura (o que é novo nesta etapa)

```
app/Observers/PedidoObserver               Dispara o e-mail quando o status muda
app/Mail/PedidoStatusAtualizado            E-mail avisando a ótica da mudança
app/Http/Controllers/Admin/PedidoPdfController   Gera o PDF (lado do painel)
resources/views/pdf/ordem-servico.blade.php      Modelo do PDF da ordem de serviço
resources/views/emails/pedido-status-atualizado.blade.php
```

O método `pdf()` que gera o PDF do lado da ótica está no mesmo
`PedidosController` da Etapa 4.

## Próximas etapas

6. Financeiro e relatórios
7. Deploy final e ajustes
