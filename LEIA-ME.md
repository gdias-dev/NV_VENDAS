# Nova Varonil - Site e Portal de Pedidos

Etapa 1: site público. Etapa 2: banco de dados e painel administrativo (Filament).
Etapa 3: cadastro público e login das próprias óticas, com aprovação.
Etapa 4: assistente de pedido, com cálculo automático de preço.
Etapa 5: aviso por e-mail de mudança de status e PDF da ordem de serviço.
Etapa 6: controle de pagamento, dashboard e relatório financeiro.
Etapa 7: deploy final e ajustes de produção (SSL, cookies, checklist completo).

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

## 8. Etapa 7 — Deploy final e ajustes

Esta é a última etapa do projeto: alguns ajustes de segurança/produção e um
checklist completo para publicar a versão final na KingHost.

**Atualizar o projeto**: baixe este zip por cima da pasta do projeto. Não tem
migration nova, mas tem 1 arquivo de código alterado (`.env.example`) — não
precisa de `composer update`.

> **Nota**: a versão anterior deste zip incluía uma mudança em
> `AppServiceProvider.php` que forçava `https://` em todos os links gerados
> pelo Laravel. Isso foi **revertido** porque quebrava o teste local (o
> `php artisan serve` só fala HTTP, e forçar https fazia o navegador tentar
> abrir os links por HTTPS na porta local, gerando um monte de erro
> "Unsupported SSL request" no terminal). Se você já tinha baixado aquele
> zip, baixe este por cima de novo — ele desfaz essa mudança. A hospedagem na
> KingHost normalmente já entrega o HTTPS certinho direto pelo Apache, sem
> precisar dessa forçação.

### O que mudou no código

- **Cookie de sessão seguro** (`.env.example`): nova variável
  `SESSION_SECURE_COOKIE`. Deixe `false` para testar localmente, e troque para
  `true` no `.env` do servidor **depois que o cadeado (https) estiver
  funcionando** — assim o navegador nunca envia o cookie de login por uma
  conexão sem criptografia.

O restante (bloqueio de arquivos internos, limite de tentativas de login,
proteção contra um usuário ver pedido de outra ótica, .htaccess, etc.) já
tinha sido preparado desde a Etapa 1 e continua valendo.

### Checklist de publicação na KingHost

1. **Domínio e SSL**: confirme que o domínio já aponta para a hospedagem e que
   o certificado SSL (cadeado) está ativo antes de divulgar o site. Se ainda
   não tiver certificado, peça para a KingHost ativar (geralmente é gratuito,
   via Let's Encrypt).
2. **Subir os arquivos**: pelo FileZilla, envie o projeto inteiro (menos
   `vendor/` e `.env`, que não vão no zip). Aponte o domínio para a pasta
   `public` do projeto — se não for possível configurar isso no painel da
   KingHost, use o `.htaccess` que já está na raiz do projeto (ele redireciona
   tudo para `public/` automaticamente).
3. **Arquivo `.env` no servidor**: copie o `.env.example`, renomeie para `.env`
   e preencha com os dados reais de produção:
   - `DB_*` com os dados do banco MySQL criado no painel da KingHost;
   - `MAIL_*` com uma conta de e-mail do domínio (para os avisos de pedido);
   - `ADMIN_SEED_EMAIL`/`ADMIN_SEED_SENHA` com um e-mail e senha fortes (você
     troca a senha de novo pelo próprio painel depois do primeiro login);
   - Deixe `SESSION_SECURE_COOKIE=false` por enquanto (você muda para `true`
     só depois de confirmar que o https está funcionando, no passo 8).
4. **Instalar dependências e gerar a chave** (via SSH, se tiver acesso; senão,
   me avise que vemos uma alternativa):
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   ```
5. **Rodar as migrations e criar o admin inicial** (só na primeira publicação;
   nas próximas, só o `migrate --force` mesmo):
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```
6. **Cache de produção** (deixa o site mais rápido — rode sempre que publicar
   uma atualização):
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
   Se em algum momento algo parecer "desatualizado" depois de mexer no `.env`
   ou nas rotas, rode `php artisan optimize:clear` para limpar tudo e depois
   gere o cache de novo.
7. **Permissões**: garanta que as pastas `storage/` e `bootstrap/cache/`
   tenham permissão de escrita (geralmente `755`, e o dono precisa ser o
   usuário do PHP na KingHost — se dermos erro de "permission denied", me
   chama que ajusto o comando certo pro painel de vocês).
8. **Confirmar o https e travar o cookie**: acesse o site pelo `https://` e
   confira se o cadeado aparece sem aviso. Depois disso, troque
   `SESSION_SECURE_COOKIE` para `true` no `.env` do servidor e rode
   `php artisan config:cache` de novo.

### Checklist de teste final (fluxo completo)

Depois de publicado, vale testar o sistema inteiro uma vez, do começo ao fim:

- [ ] Site público abre certinho (`/`, `/lentes-e-tratamentos`, `/como-pedir`,
      `/sobre`, `/contato`) e o formulário de contato envia e-mail.
- [ ] Uma ótica nova consegue se cadastrar em `/area-das-oticas/cadastro`.
- [ ] O cadastro aparece "Pendente" no painel administrativo, e ao aprovar, a
      ótica recebe o e-mail de aprovação.
- [ ] A ótica aprovada consegue fazer login e ver o painel dela.
- [ ] Fazer login com e-mail/senha do **admin** na tela `/area-das-oticas`
      redireciona para `/admin` (ajuste feito antes da Etapa 6).
- [ ] Um pedido completo, passando por todos os 7 passos do assistente
      (cliente, receita, lente, tratamentos, montagem com armação/foto,
      paciente/profissional, resumo), é enviado com sucesso.
- [ ] O laboratório recebe o e-mail de "novo pedido", e a ótica recebe o
      e-mail quando o status muda.
- [ ] O PDF da ordem de serviço abre certinho, com todos os dados (incluindo
      armação e paciente/profissional quando preenchidos).
- [ ] Registrar pagamento de um pedido funciona, e aparece certinho no
      Dashboard e no Relatório financeiro (inclusive o "Exportar CSV").
- [ ] Depois de tudo testado, **troque a senha do admin** (a que veio do
      `ADMIN_SEED_SENHA`) pelo próprio painel.

## 9. Checagem de segurança + bloqueio de login após tentativas erradas

Foi feita uma revisão de segurança no sistema inteiro (SQL injection, acesso
indevido a dados de outra ótica, permissões do painel administrativo, upload
de arquivos, etc.) e implementado o bloqueio de login pedido.

**Atualizar o projeto**: baixe este zip por cima da pasta do projeto. Não tem
migration nova nem pacote novo — só arquivos de código.

### O que foi revisado (e já estava OK)

- **SQL injection**: o projeto usa o Eloquent/Query Builder do Laravel em
  tudo (nunca monta SQL colando texto do usuário), inclusive nos 3 lugares
  que usam consulta "crua" (`selectRaw`/`orderByRaw`) — nesses, o texto é
  sempre fixo no código, nunca vem do usuário. Não há brecha de SQL injection.
- **Acesso a dados de outra ótica**: já era checado — uma ótica só vê os
  próprios pedidos (`/area-das-oticas/pedidos/{id}`), tentar abrir o pedido de
  outra ótica retorna "não encontrado".
- **Upload de foto da armação**: só aceita imagem de verdade (não dá pra
  disfarçar um arquivo `.php` de imagem), limite de 5 MB, nome do arquivo
  trocado por um aleatório ao salvar.
- **Senhas**: sempre guardadas com hash (nunca em texto puro), nunca aparecem
  em log.
- **CSRF, cadastro de ótica, formulário de contato**: já tinham proteção
  (token CSRF automático do Laravel, limite de envios por minuto, campo-armadilha
  invisível contra robôs no formulário de contato).

### O que foi corrigido

- **Falha de permissão (a mais importante)**: no painel administrativo, a
  tela de "Usuários do painel" já escondia o menu para quem não é
  administrador, mas **não bloqueava o acesso direto pela URL** — um usuário
  com papel "Produção" que descobrisse o link certo conseguiria criar um
  usuário novo ou editar um existente (inclusive trocando o próprio papel
  para "Administrador"). Isso foi fechado: agora só administrador consegue
  criar, editar ou excluir usuários do painel, em qualquer caminho de acesso.
- **Bloqueio após tentativas erradas de login** (o que você pediu): tanto no
  login das óticas (`/area-das-oticas`) quanto no login do painel
  administrativo (`/admin/login`), depois de **5 tentativas erradas**, a
  pessoa (identificada pelo e-mail digitado + o computador/rede de onde
  tentou) fica **bloqueada por 15 minutos** antes de poder tentar de novo. O
  contador zera assim que o login certo é feito.
- **Cabeçalhos de segurança extras**: adicionado no servidor (`.htaccess`)
  um reforço contra dois ataques comuns de navegador — clickjacking (alguém
  tentar "esconder" o seu site dentro de outro site pra enganar cliques) e
  MIME sniffing.

### Observação sobre o bloqueio de 15 minutos

O bloqueio é por **e-mail + endereço de rede (IP)** de quem tentou, não só
pelo e-mail sozinho. Isso é proposital: se fosse só pelo e-mail, qualquer
pessoa mal-intencionada poderia bloquear a conta de outra pessoa de propósito,
só errando a senha dela 5 vezes de qualquer lugar. Com e-mail + IP, cada
"origem" tem seu próprio contador — então, se alguém tentar invadir uma conta
específica usando vários computadores/redes diferentes, cada um tem seu
próprio limite de 5 tentativas (é uma troca comum entre segurança e não travar
sem necessidade; se quiser um bloqueio mais rígido, por e-mail sozinho, é só
pedir).

### Recomendação (não aplicada ainda, é decisão sua)

As telas de **Tabela de preços**, **Lentes** e **Tratamentos**, no painel
administrativo, hoje podem ser criadas/editadas/excluídas por qualquer
usuário do painel (admin ou produção). Se fizer sentido pro seu negócio que só
o administrador mexa em preços, me avise que eu restrinjo do mesmo jeito que
fiz com "Usuários".

### Testar

1. No login das óticas ou no login do admin, erre a senha 5 vezes seguidas —
   na 6ª tentativa (mesmo com a senha certa), deve aparecer a mensagem de
   bloqueio temporário.
2. Espere 15 minutos (ou, para testar mais rápido, veja com Guilherme como
   reduzir temporariamente o tempo em ambiente local) e confirme que volta a
   deixar tentar.
3. Logado como um usuário com papel "Produção", tente acessar diretamente
   `/admin/users` e `/admin/users/create` — o sistema deve negar o acesso
   (403), mesmo digitando a URL na mão.

## Próximas etapas

Nenhuma — o roteiro original de 7 etapas está concluído. Qualquer ajuste ou
funcionalidade nova a partir daqui é sob demanda.
