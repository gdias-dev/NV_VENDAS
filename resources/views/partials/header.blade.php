@php
    $links = [
        ['home', 'Início'],
        ['lentes', 'Lentes e tratamentos'],
        ['como-pedir', 'Como pedir'],
        ['sobre', 'Sobre'],
        ['contato', 'Contato'],
    ];

    $oticaLogada = auth('otica')->check();
    $rotaAreaOticas = $oticaLogada ? route('portal.painel') : route('area-oticas');
    $textoAreaOticas = $oticaLogada ? 'Meu painel' : 'Área das óticas';
@endphp
<header class="sticky top-0 z-40 border-b border-line bg-white/90 backdrop-blur">
    <div class="brand-bar"></div>
    <div class="container-x flex h-16 items-center justify-between gap-4 sm:h-[72px]">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="Nova Varonil Laboratório Óptico - página inicial">
            <img src="{{ asset('img/logo.png') }}" alt="Nova Varonil Laboratório Óptico desde 1964" width="1200" height="240" class="h-8 w-auto sm:h-10">
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Principal">
            @foreach ($links as $l)
                <a href="{{ route($l[0]) }}" class="nav-link" aria-current="{{ request()->routeIs($l[0]) ? 'page' : 'false' }}">{{ $l[1] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ $rotaAreaOticas }}" class="btn btn-primary hidden !py-2.5 sm:inline-flex">
                @include('partials.icon', ['icon' => 'lock', 'class' => 'h-4 w-4'])
                {{ $textoAreaOticas }}
            </a>
            <button id="menu-toggle" type="button" class="grid h-11 w-11 place-items-center rounded-xl border border-line text-brand-navy lg:hidden" aria-expanded="false" aria-controls="menu-mobile" aria-label="Abrir menu">
                @include('partials.icon', ['icon' => 'menu'])
            </button>
        </div>
    </div>

    <nav id="menu-mobile" class="hidden border-t border-line bg-white lg:hidden" aria-label="Menu móvel">
        <div class="container-x flex flex-col gap-1 py-3">
            @foreach ($links as $l)
                <a href="{{ route($l[0]) }}" class="nav-link !py-3 !text-base" aria-current="{{ request()->routeIs($l[0]) ? 'page' : 'false' }}">{{ $l[1] }}</a>
            @endforeach
            <a href="{{ $rotaAreaOticas }}" class="btn btn-primary mt-2">
                @include('partials.icon', ['icon' => 'lock', 'class' => 'h-4 w-4'])
                {{ $textoAreaOticas }}
            </a>
        </div>
    </nav>
</header>
