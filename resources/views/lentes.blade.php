@extends('layouts.app')

@section('titulo', 'Lentes e tratamentos | Nova Varonil')
@section('descricao', 'Conheça as lentes monofocais, multifocais e bifocais e os tratamentos disponíveis no laboratório Nova Varonil.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Lentes e tratamentos',
    'subtitulo' => 'Uma linha completa para atender qualquer receita, com ou sem montagem.',
])

<section class="section">
    <div class="container-x">
        <div class="max-w-2xl">
            <p class="eyebrow">Tipos de lentes</p>
            <h2 class="title mt-3">Escolha pelo tipo de visão</h2>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach (config('nova.lentes') as $l)
                <article class="card card-hover reveal flex flex-col">
                    <span class="icon-wrap">@include('partials.icon', ['icon' => $l['icone']])</span>
                    <h3 class="mt-4 text-xl font-bold text-brand-navy">{{ $l['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $l['texto'] }}</p>
                    <ul class="mt-5 space-y-2 border-t border-line pt-5 text-sm text-slate-700">
                        @foreach ($l['itens'] as $item)
                            <li class="flex gap-2">
                                <span class="mt-0.5 text-brand-green">@include('partials.icon', ['icon' => 'check', 'class' => 'h-4 w-4'])</span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-mist">
    <div class="container-x">
        <div class="max-w-2xl">
            <p class="eyebrow">Tratamentos</p>
            <h2 class="title mt-3">Mais conforto e proteção para o cliente final</h2>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2">
            @foreach (config('nova.tratamentos') as $t)
                <article class="reveal flex gap-4 rounded-2xl border border-line bg-white p-6">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-purple/10 text-brand-purple">@include('partials.icon', ['icon' => $t['icone']])</span>
                    <div>
                        <h3 class="text-lg font-bold text-brand-navy">{{ $t['titulo'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $t['texto'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container-x">
        <div class="grid items-center gap-8 rounded-3xl border border-line bg-white p-8 shadow-soft md:grid-cols-12 md:p-12">
            <div class="md:col-span-8">
                <h2 class="font-display text-2xl font-extrabold text-brand-navy sm:text-3xl">Quer ver os valores?</h2>
                <p class="mt-3 text-slate-600">A tabela de preços completa, com lentes, tratamentos e montagem, fica disponível na área das óticas depois da aprovação do cadastro.</p>
            </div>
            <div class="flex flex-col gap-3 md:col-span-4">
                <a href="{{ route('contato') }}" class="btn btn-primary">Solicitar cadastro</a>
                <a href="{{ route('como-pedir') }}" class="btn btn-outline">Como pedir</a>
            </div>
        </div>
    </div>
</section>

@endsection
