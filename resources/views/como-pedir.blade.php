@extends('layouts.app')

@section('titulo', 'Como pedir | Nova Varonil')
@section('descricao', 'Veja como a sua ótica faz pedidos de lentes com ou sem montagem no portal da Nova Varonil.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Como pedir',
    'subtitulo' => 'Do cadastro da ótica ao acompanhamento da entrega, tudo em um só lugar.',
])

<section class="section">
    <div class="container-x">
        <ol class="relative mx-auto max-w-3xl space-y-6 border-l-2 border-brand-cyan/30 pl-8">
            @foreach (config('nova.passos') as $i => $p)
                <li class="reveal relative">
                    <span class="absolute -left-[3.05rem] grid h-9 w-9 place-items-center rounded-full bg-brand-navy font-display text-sm font-extrabold text-white ring-4 ring-white">{{ $i + 1 }}</span>
                    <div class="card">
                        <div class="flex items-center gap-3">
                            <span class="icon-wrap !h-10 !w-10">@include('partials.icon', ['icon' => $p['icone'], 'class' => 'h-5 w-5'])</span>
                            <h2 class="text-lg font-bold text-brand-navy">{{ $p['titulo'] }}</h2>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $p['texto'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="section bg-mist">
    <div class="container-x">
        <div class="mx-auto max-w-3xl">
            <p class="eyebrow">Dúvidas frequentes</p>
            <h2 class="title mt-3">Perguntas e respostas</h2>

            <div class="mt-8 space-y-3">
                @foreach (config('nova.faq') as $f)
                    <details class="group rounded-2xl border border-line bg-white p-5 open:shadow-soft">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 text-base font-bold text-brand-navy">
                            {{ $f['p'] }}
                            <span class="chev shrink-0 text-brand-purple transition">@include('partials.icon', ['icon' => 'chev'])</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $f['r'] }}</p>
                    </details>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <p class="text-slate-600">Não encontrou a resposta?</p>
                <a href="{{ route('contato') }}" class="btn btn-primary mt-4">Fale com o laboratório</a>
            </div>
        </div>
    </div>
</section>

@endsection
