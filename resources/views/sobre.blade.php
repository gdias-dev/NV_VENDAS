@extends('layouts.app')

@section('titulo', 'Sobre o laboratório | Nova Varonil')
@section('descricao', 'Conheça a Nova Varonil, laboratório óptico no Centro do Rio de Janeiro que produz e monta lentes desde 1964.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Sobre a Nova Varonil',
    'subtitulo' => 'Laboratório óptico no Centro do Rio de Janeiro, produzindo lentes e tratamentos desde '.config('nova.fundacao').'.',
])

<section class="section">
    <div class="container-x grid items-center gap-12 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <p class="eyebrow">Nossa história</p>
            <h2 class="title mt-3">Mais de seis décadas cuidando da visão junto com as óticas</h2>
            <div class="mt-6 space-y-4 text-base leading-relaxed text-slate-600">
                <p>Desde {{ config('nova.fundacao') }}, a Nova Varonil trabalha lado a lado com óticas, produzindo lentes de qualidade e oferecendo tratamentos e montagem no próprio laboratório.</p>
                <p>Agora, essa experiência ganha um portal: a ótica faz o pedido online, escolhe entre receber apenas a lente ou o óculos montado e acompanha cada etapa sem depender de ligações.</p>
                <p>A tecnologia muda, mas o compromisso continua o mesmo: entregar precisão, cumprir o combinado e ser um parceiro de confiança para o seu negócio.</p>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="grid grid-cols-2 gap-4">
                <div class="card text-center">
                    <p class="font-display text-4xl font-extrabold text-brand-purple">{{ config('nova.fundacao') }}</p>
                    <p class="mt-1 text-sm font-semibold text-slate-600">Ano de fundação</p>
                </div>
                <div class="card text-center">
                    <p class="font-display text-4xl font-extrabold text-brand-cyan">{{ date('Y') - config('nova.fundacao') }}</p>
                    <p class="mt-1 text-sm font-semibold text-slate-600">Anos de tradição</p>
                </div>
                <div class="card col-span-2 text-center">
                    <p class="font-display text-2xl font-extrabold text-brand-navy">Centro do Rio de Janeiro</p>
                    <p class="mt-1 text-sm font-semibold text-slate-600">Onde produzimos e montamos</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-mist">
    <div class="container-x">
        <div class="mx-auto max-w-2xl text-center">
            <p class="eyebrow">O que nos guia</p>
            <h2 class="title mt-3">Nossos compromissos com a ótica parceira</h2>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('nova.diferenciais') as $d)
                <article class="card card-hover reveal">
                    <span class="icon-wrap">@include('partials.icon', ['icon' => $d['icone']])</span>
                    <h3 class="mt-4 text-base font-bold text-brand-navy">{{ $d['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $d['texto'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container-x text-center">
        <h2 class="font-display text-3xl font-extrabold text-brand-navy">Vamos trabalhar juntos?</h2>
        <p class="lead mx-auto mt-3 max-w-xl">Solicite o cadastro da sua ótica e conheça o portal de pedidos.</p>
        <a href="{{ route('contato') }}" class="btn btn-primary mt-6">Solicitar cadastro</a>
    </div>
</section>

@endsection
