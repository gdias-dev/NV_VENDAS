<!DOCTYPE html>
<html lang="pt-BR" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Nova Varonil | Laboratório Óptico desde 1964')</title>
    <meta name="description" content="@yield('descricao', 'Laboratório óptico no Centro do Rio de Janeiro desde 1964. Lentes com ou sem montagem para óticas, com pedido online e acompanhamento.')">
    <meta name="theme-color" content="#12306d">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Nova Varonil Laboratório Óptico">
    <meta property="og:title" content="@yield('titulo', 'Nova Varonil | Laboratório Óptico desde 1964')">
    <meta property="og:description" content="@yield('descricao', 'Lentes com ou sem montagem para óticas, com pedido online e acompanhamento.')">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="og:locale" content="pt_BR">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <script>document.documentElement.classList.remove('no-js');</script>
    @livewireStyles
</head>
<body class="flex min-h-screen flex-col">
    <a href="#conteudo" class="sr-only rounded-lg bg-brand-navy px-4 py-2 text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50">Ir para o conteúdo</a>

    @include('partials.header')

    <main id="conteudo" class="flex-1">
        @yield('conteudo')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
    @livewireScripts
</body>
</html>
