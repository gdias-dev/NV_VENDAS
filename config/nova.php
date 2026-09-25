<?php

/*
|--------------------------------------------------------------------------
| Dados e conteúdo do site Nova Varonil
|--------------------------------------------------------------------------
| Contatos vêm do .env (preencha lá). Os textos de conteúdo ficam aqui para
| você poder ajustar sem mexer nas telas (views).
*/

return [

    'nome' => 'Nova Varonil',
    'subtitulo' => 'Laboratório Óptico',
    'fundacao' => 1964,

    // ---- Contatos (preencher no .env) -----------------------------------
    'email_contato' => env('NOVA_EMAIL_CONTATO'),
    'telefone' => env('NOVA_TELEFONE'),               // ex.: (21) 2222-3333
    'whatsapp' => env('NOVA_WHATSAPP'),               // somente números com DDI: 5521999999999
    'endereco' => env('NOVA_ENDERECO', 'Centro, Rio de Janeiro - RJ'),
    'horario' => env('NOVA_HORARIO', 'Segunda a sexta, em horário comercial'),
    'instagram' => env('NOVA_INSTAGRAM', 'https://www.instagram.com/novavaronil'),
    'mapa_url' => env('NOVA_MAPA_URL'),               // link do Google Maps (opcional)

    // ---- Como funciona o pedido -----------------------------------------
    'passos' => [
        ['icone' => 'building', 'titulo' => 'Cadastre a sua ótica', 'texto' => 'Envie os dados da ótica. Após a aprovação, você recebe o acesso ao portal e à tabela de preços.'],
        ['icone' => 'clipboard', 'titulo' => 'Monte o pedido', 'texto' => 'Informe a receita, escolha a lente e os tratamentos e veja o valor calculado na hora.'],
        ['icone' => 'wrench', 'titulo' => 'Com ou sem montagem', 'texto' => 'Peça só a lente ou deixe que o laboratório monte na armação do seu cliente.'],
        ['icone' => 'truck', 'titulo' => 'Acompanhe até a entrega', 'texto' => 'Veja o status de cada pedido, da produção até a expedição, sem precisar ligar.'],
    ],

    // ---- Tipos de lentes --------------------------------------------------
    'lentes' => [
        [
            'icone' => 'eye',
            'titulo' => 'Visão simples (monofocais)',
            'texto' => 'Para longe ou para perto. Indicadas para correção de miopia, hipermetropia e astigmatismo.',
            'itens' => ['Miopia, hipermetropia e astigmatismo', 'Várias opções de material e índice', 'Consulte as opções disponíveis na tabela de preços'],
        ],
        [
            'icone' => 'layers',
            'titulo' => 'Multifocais (progressivas)',
            'texto' => 'Visão nítida em todas as distâncias, sem a linha divisória das lentes bifocais.',
            'itens' => ['Perto, intermediário e longe', 'Opções de corredor e tecnologia', 'Medidas personalizadas pela montagem'],
        ],
        [
            'icone' => 'split',
            'titulo' => 'Bifocais',
            'texto' => 'Duas áreas de visão na mesma lente: uma para longe e outra para perto.',
            'itens' => ['Solução tradicional e acessível', 'Diferentes formatos de segmento'],
        ],
    ],

    // ---- Tratamentos ------------------------------------------------------
    'tratamentos' => [
        ['icone' => 'sparkle', 'titulo' => 'Antirreflexo (AR)', 'texto' => 'Reduz reflexos e melhora o conforto visual, principalmente à noite e diante de telas.'],
        ['icone' => 'sun', 'titulo' => 'Fotossensíveis', 'texto' => 'Escurecem ao ar livre e clareiam em ambientes internos.'],
        ['icone' => 'monitor', 'titulo' => 'Filtro de luz azul', 'texto' => 'Indicado para quem passa muitas horas em computadores, celulares e tablets.'],
        ['icone' => 'shield', 'titulo' => 'Endurecimento', 'texto' => 'Camada de proteção que aumenta a resistência a riscos no dia a dia.'],
    ],

    // ---- Diferenciais -----------------------------------------------------
    'diferenciais' => [
        ['icone' => 'clock', 'titulo' => 'Tradição desde 1964', 'texto' => 'Mais de seis décadas fabricando e montando lentes para óticas do Rio de Janeiro.'],
        ['icone' => 'wrench', 'titulo' => 'Montagem no laboratório', 'texto' => 'Você escolhe: receber apenas a lente ou o óculos montado e pronto para entregar.'],
        ['icone' => 'chart', 'titulo' => 'Pedido sob controle', 'texto' => 'Acompanhe o andamento e consulte o histórico de pedidos da sua ótica quando precisar.'],
        ['icone' => 'chat', 'titulo' => 'Atendimento próximo', 'texto' => 'Uma equipe que conhece o seu negócio e ajuda a resolver dúvidas técnicas de receita.'],
    ],

    // ---- Perguntas frequentes --------------------------------------------
    'faq' => [
        ['p' => 'Preciso ser uma ótica para comprar?', 'r' => 'O portal é voltado a óticas e profissionais da área. Se você tem uma ótica, basta solicitar o cadastro para análise.'],
        ['p' => 'Como vejo os preços das lentes?', 'r' => 'A tabela completa fica disponível na área das óticas, depois que o cadastro é aprovado.'],
        ['p' => 'Posso pedir só a lente, sem montagem?', 'r' => 'Sim. No pedido você escolhe entre receber apenas as lentes ou com montagem na armação do cliente.'],
        ['p' => 'Como acompanho o andamento do pedido?', 'r' => 'Cada pedido tem um status atualizado no portal, do recebimento até a expedição.'],
        ['p' => 'E se eu tiver dúvida sobre uma receita?', 'r' => 'Fale com o laboratório pelo formulário de contato ou WhatsApp e a equipe ajuda a definir a melhor lente.'],
    ],

];
