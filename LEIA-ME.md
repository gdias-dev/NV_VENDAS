# Nova Varonil - Site e Portal de Pedidos

Etapa 1: site público. Etapa 2: banco de dados e painel administrativo (Filament).
Etapa 3: cadastro público e login das próprias óticas, com aprovação.
Etapa 4: assistente de pedido, com cálculo automático de preço.
Etapa 5: aviso por e-mail de mudança de status e PDF da ordem de serviço.
Etapa 6: controle de pagamento, dashboard e relatório financeiro.

## 1. Atualizar o projeto

Baixe este zip **por cima da pasta do projeto** (ele não inclui `vendor/` nem `.env`).

Esta etapa **não adiciona pacotes novos** (não precisa rodar `composer update`), só
uma migration nova. Rode, na pasta do projeto:

```bash
php artisan migrate
```

Isso adiciona os campos de pagamento (`pago`, `pago_em`, `forma_pagamento`) na
tabela de pedidos.

## 2. O que mudou

- **Registrar pagamento**: cada pedido, no painel administrativo (Pedidos), agora
  tem um botão **"Registrar pagamento"**. Você marca se está pago, escolhe a forma
  (Dinheiro, Pix, Cartão, Boleto ou Outro) e a data — e isso fica salvo no pedido,
  visível também na tela de detalhe (seção "Financeiro").
- **Dashboard**: a tela inicial do painel administrativo (`/admin`) agora mostra:
  - Três números do mês atual: **faturado**, **recebido** e **a receber** (pedidos
    cancelados não entram nessas contas).
  - Um gráfico de faturamento dos últimos 14 dias.
- **Relatório financeiro**: novo item de menu, **Financeiro → Relatório financeiro**,
  com filtro por período (data início/fim) e por ótica. Mostra o total faturado,
  recebido, a receber, o ticket médio e um ranking de faturamento por ótica no
  período escolhido. Tem um botão **"Exportar CSV"** que baixa essa lista para abrir
  no Excel.

## 3. Testar localmente

```bash
php artisan serve
```

1. No painel administrativo (`/admin` → **Pedidos**), abra um pedido e clique em
   **"Registrar pagamento"**. Marque como pago, escolha a forma e confirme.
2. Abra o detalhe do pedido (ícone de olho) e veja a seção **"Financeiro"** com a
   situação atualizada.
3. Volte para a tela inicial do painel (**Dashboard**, no menu) e veja os números do
   mês e o gráfico dos últimos 14 dias.
4. Vá em **Financeiro → Relatório financeiro**, mude as datas e a ótica no filtro, e
   confira se os números batem. Clique em **"Exportar CSV"** e abra o arquivo
   baixado.

## 4. Publicar na KingHost

Os passos são os mesmos de antes (FileZilla, apontar para `public`, etc.). Depois de
subir os arquivos novos, rode no servidor (ou peça ajuda, se não tiver SSH):

```bash
php artisan migrate --force
```

Não precisa rodar `composer install`/`update` nesta etapa (nenhum pacote novo foi
adicionado).

## 5. Estrutura (o que é novo nesta etapa)

```
app/Filament/Widgets/FaturamentoOverview        Números do mês, no dashboard
app/Filament/Widgets/FaturamentoPorDiaChart     Gráfico dos últimos 14 dias
app/Filament/Pages/RelatorioFinanceiro          Página "Relatório financeiro"
app/Http/Controllers/Admin/RelatorioFinanceiroController   Exportação do CSV
resources/views/filament/pages/relatorio-financeiro.blade.php
```

A ação "Registrar pagamento" e a seção "Financeiro" ficam no mesmo
`PedidoResource` das etapas anteriores.

## Próximas etapas

7. Deploy final e ajustes
