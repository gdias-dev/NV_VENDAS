<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <svg class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 opacity-60" viewBox="0 0 400 400" fill="none" aria-hidden="true">
        <circle cx="200" cy="200" r="190" stroke="#00afef" stroke-opacity=".2" stroke-width="2"/>
        <circle cx="200" cy="200" r="130" stroke="#3e4095" stroke-opacity=".18" stroke-width="2"/>
        <circle cx="200" cy="200" r="70" fill="#00afef" fill-opacity=".08"/>
    </svg>
    <div class="container-x relative py-14 sm:py-20">
        <nav aria-label="Você está em" class="text-xs font-semibold text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-purple">Início</a>
            <span class="mx-1.5">/</span>
            <span class="text-brand-navy">{{ $titulo }}</span>
        </nav>
        <h1 class="mt-4 max-w-3xl text-4xl font-extrabold text-brand-navy sm:text-5xl">{{ $titulo }}</h1>
        @isset($subtitulo)
            <p class="lead mt-4 max-w-2xl">{{ $subtitulo }}</p>
        @endisset
    </div>
</section>
