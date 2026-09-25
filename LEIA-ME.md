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

## 6. Adição: formato e medidas da armação, na montagem

Depois da Etapa 6, foi adicionado um reforço no passo de montagem do assistente de
pedido (Etapa 4), para ajudar o laboratório na hora de montar a lente.

**Atualizar o projeto**: baixe este zip por cima da pasta do projeto (não precisa de
`composer update`) e rode:

```bash
php artisan migrate
php artisan storage:link
```

O `storage:link` só precisa ser rodado **uma vez** (se você já rodou antes, não tem
problema rodar de novo, não dá erro). Ele é o que faz as fotos de armação enviadas
pelas óticas aparecerem certinho no navegador e no PDF.

**O que mudou**: quando a ótica marca **"Quero montagem"** no pedido, agora aparecem
campos extras (sempre opcionais — se a ótica não preencher, o pedido continua sendo
enviado normalmente):

- **Formato da armação**: uma lista para escolher o formato (quadrada, aviador,
  redonda, etc.), ou a opção de **enviar uma foto da armação** em vez de escolher.
- **Medidas da armação**: MVA, MHA, DMA, Ponte e DPA (em mm) — e um **diâmetro
  estimado** calculado a partir da DMA (ou da MHA, se a DMA não vier preenchida).
  É só uma estimativa para ajudar; o laboratório sempre confere antes de cortar a
  lente.
- **Clip-on**: Não / Sim / Não informado.
- Um marcador se **a armação será enviada** junto para a montagem.

Tudo isso aparece na tela de detalhe do pedido (para a ótica), no detalhe do pedido
no painel administrativo (nova seção "Armação") e na ordem de serviço em PDF.

**Testar**: no portal da ótica, faça um novo pedido marcando "Quero montagem" e
preenchendo os campos novos (inclusive testando o envio de uma foto). Confira se
aparece certinho no detalhe do pedido, no painel administrativo e no PDF.

**Publicar na KingHost**: depois de subir os arquivos novos e rodar
`php artisan migrate --force`, rode também `php artisan storage:link` no servidor
(se tiver acesso SSH). Se não tiver SSH na KingHost, me avise — nesse caso, o link
simbólico às vezes precisa ser criado de outro jeito, e eu te ajudo a resolver.

## 7. Adição: paciente e profissional da receita

Depois da adição de armação, foi criado um **novo passo no assistente de pedido**
(entre "Montagem" e "Resumo"), para registrar quem passou a receita e alguns
dados do paciente.

**Atualizar o projeto**: baixe este zip por cima da pasta do projeto (não precisa de
`composer update`) e rode:

```bash
php artisan migrate
```

**O que mudou**: antes de mostrar o resumo final, o assistente agora pergunta
(tudo opcional, exceto o tipo de profissional):

- **Quem passou a receita**: Médico (oftalmologista) ou Optometrista.
- **Nome do profissional** — o rótulo muda de acordo com a escolha acima.
- **UF do CRM e CRM** — só aparece quando é "Médico", já que optometrista não
  tem CRM.
- **Dados do paciente**: iniciais, idade e complemento (um campo livre, para
  qualquer observação extra sobre o paciente).

Esses dados aparecem no resumo do pedido (assistente), na tela de detalhe do
pedido (portal da ótica), no painel administrativo (nova seção "Paciente e
profissional") e na ordem de serviço em PDF — sempre que algum desses campos
for preenchido.

**Testar**: no portal da ótica, faça um novo pedido e, no passo novo (antes do
resumo), preencha os dados de profissional e paciente. Confira se aparece
certinho no resumo, no detalhe do pedido, no painel administrativo e no PDF.
Teste também deixando tudo em branco, pra confirmar que o pedido continua
sendo enviado normalmente.

**Publicar na KingHost**: os passos são os mesmos de sempre — depois de subir
os arquivos novos, rode `php artisan migrate --force` no servidor.

## Próximas etapas

7. Deploy final e ajustes
