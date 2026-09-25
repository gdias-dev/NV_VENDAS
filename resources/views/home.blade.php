@extends('layouts.app')

@section('conteudo')

{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <svg class="pointer-events-none absolute -right-40 -top-40 h-[640px] w-[640px] opacity-70" viewBox="0 0 640 640" fill="none" aria-hidden="true">
        <circle cx="320" cy="320" r="300" stroke="#00afef" stroke-opacity=".18" stroke-width="2"/>
        <circle cx="320" cy="320" r="230" stroke="#3e4095" stroke-opacity=".16" stroke-width="2"/>
        <circle cx="320" cy="320" r="160" stroke="#2a833f" stroke-opacity=".18" stroke-width="2"/>
        <circle cx="320" cy="320" r="90" fill="#00afef" fill-opacity=".08"/>
    </svg>

    <div class="container-x relative grid items-center gap-12 py-16 sm:py-24 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <p class="eyebrow">Laboratório óptico desde {{ config('nova.fundacao') }}</p>
            <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] text-brand-navy sm:text-5xl lg:text-[3.4rem]">
                Lentes de precisão para a sua ótica, com a <span class="text-brand-purple">tradição de {{ date('Y') - config('nova.fundacao') }} anos</span>.
            </h1>
            <p class="lead mt-6 max-w-xl">
                Peça lentes com ou sem montagem, escolha os tratamentos e acompanhe cada pedido pelo portal. Simples para a sua equipe, confiável para o seu cliente.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('area-oticas') }}" class="btn btn-primary">
                    @include('partials.icon', ['icon' => 'lock', 'class' => 'h-4 w-4'])
                    Acessar área das óticas
                </a>
                <a href="{{ route('contato') }}" class="btn btn-outline">
                    Quero ser cliente
                    @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])
                </a>
            </div>

            <ul class="mt-10 grid max-w-xl gap-3 text-sm font-semibold text-slate-700 sm:grid-cols-3">
                @foreach (['Com ou sem montagem', 'Pedido online', 'Acompanhamento do status'] as $item)
                    <li class="flex items-center gap-2">
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand-green/10 text-brand-green">
                            @include('partials.icon', ['icon' => 'check', 'class' => 'h-3.5 w-3.5'])
                        </span>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Ilustração do portal (exemplo) --}}
        <div class="lg:col-span-5">
            <div class="relative mx-auto max-w-sm">
                <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-br from-brand-cyan/20 via-brand-purple/10 to-brand-green/10 blur-2xl"></div>
                <div class="rounded-3xl border border-line bg-white p-6 shadow-soft">
                    <div class="flex items-center justify-between">
                        <p class="font-display text-sm font-bold text-brand-navy">Pedido de exemplo</p>
                        <span class="rounded-full bg-brand-cyan/15 px-3 py-1 text-xs font-bold text-brand-navy">Em produção</span>
                    </div>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between border-b border-line pb-3"><dt class="text-slate-500">Lente</dt><dd class="font-semibold">Multifocal</dd></div>
                        <div class="flex justify-between border-b border-line pb-3"><dt class="text-slate-500">Tratamento</dt><dd class="font-semibold">Antirreflexo</dd></div>
                        <div class="flex justify-between border-b border-line pb-3"><dt class="text-slate-500">Montagem</dt><dd class="font-semibold">Com montagem</dd></div>
                    </dl>

                    <ol class="mt-6 flex items-center" aria-label="Andamento do pedido">
                        <li class="flex flex-col items-center gap-1.5 text-center">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-green text-white">@include('partials.icon', ['icon' => 'check', 'class' => 'h-4 w-4'])</span>
                            <span class="text-[11px] font-semibold text-slate-600">Recebido</span>
                        </li>
                        <li aria-hidden="true" class="mb-5 h-0.5 flex-1 bg-brand-green"></li>
                        <li class="flex flex-col items-center gap-1.5 text-center">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-cyan text-white ring-4 ring-brand-cyan/20">@include('partials.icon', ['icon' => 'wrench', 'class' => 'h-4 w-4'])</span>
                            <span class="text-[11px] font-semibold text-brand-navy">Produção</span>
                        </li>
                        <li aria-hidden="true" class="mb-5 h-0.5 flex-1 bg-line"></li>
                        <li class="flex flex-col items-center gap-1.5 text-center">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-mist text-slate-400 ring-1 ring-line">@include('partials.icon', ['icon' => 'truck', 'class' => 'h-4 w-4'])</span>
                            <span class="text-[11px] font-semibold text-slate-400">Expedição</span>
                        </li>
                    </ol>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400">Ilustração do acompanhamento de pedidos</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ COMO FUNCIONA ============ --}}
<section class="section">
    <div class="container-x">
        <div class="max-w-2xl reveal">
            <p class="eyebrow">Como funciona</p>
            <h2 class="title mt-3">Do cadastro à entrega, em quatro passos</h2>
            <p class="lead mt-4">Um fluxo pensado para a rotina da ótica: menos ligações, menos retrabalho e mais controle sobre cada pedido.</p>
        </div>

        <ol class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('nova.passos') as $i => $p)
                <li class="card card-hover reveal relative">
                    <span class="absolute right-5 top-4 font-display text-4xl font-extrabold text-brand-cyan/20">{{ $i + 1 }}</span>
                    <span class="icon-wrap">@include('partials.icon', ['icon' => $p['icone']])</span>
                    <h3 class="mt-4 text-lg font-bold text-brand-navy">{{ $p['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $p['texto'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="mt-10 text-center">
            <a href="{{ route('como-pedir') }}" class="btn btn-outline">Ver detalhes do processo @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])</a>
        </div>
    </div>
</section>

{{-- ============ LENTES E TRATAMENTOS ============ --}}
<section class="section bg-mist">
    <div class="container-x">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div class="max-w-2xl reveal">
                <p class="eyebrow">Produtos</p>
                <h2 class="title mt-3">Lentes e tratamentos para cada receita</h2>
                <p class="lead mt-4">Do visão simples ao multifocal, com os tratamentos que o seu cliente precisa.</p>
            </div>
            <a href="{{ route('lentes') }}" class="btn btn-primary self-start md:self-auto">Ver todos os produtos @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])</a>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
            @foreach (config('nova.lentes') as $l)
                <article class="card card-hover reveal">
                    <span class="icon-wrap">@include('partials.icon', ['icon' => $l['icone']])</span>
                    <h3 class="mt-4 text-lg font-bold text-brand-navy">{{ $l['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $l['texto'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('nova.tratamentos') as $t)
                <article class="reveal flex gap-4 rounded-2xl border border-line bg-white p-5">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-brand-purple/10 text-brand-purple">@include('partials.icon', ['icon' => $t['icone'], 'class' => 'h-5 w-5'])</span>
                    <div>
                        <h3 class="text-sm font-bold text-brand-navy">{{ $t['titulo'] }}</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $t['texto'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ DIFERENCIAIS ============ --}}
<section class="section">
    <div class="container-x">
        <div class="mx-auto max-w-2xl text-center reveal">
            <p class="eyebrow">Por que a Nova Varonil</p>
            <h2 class="title mt-3">Experiência de laboratório, agilidade de portal</h2>
        </div>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('nova.diferenciais') as $d)
                <div class="reveal text-center">
                    <span class="icon-wrap mx-auto !h-14 !w-14 !rounded-2xl">@include('partials.icon', ['icon' => $d['icone'], 'class' => 'h-7 w-7'])</span>
                    <h3 class="mt-5 text-base font-bold text-brand-navy">{{ $d['titulo'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $d['texto'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ CTA ============ --}}
<section class="pb-16 sm:pb-24">
    <div class="container-x">
        <div class="relative overflow-hidden rounded-3xl bg-brand-navy px-6 py-12 text-center text-white sm:px-12 sm:py-16">
            <svg class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 opacity-30" viewBox="0 0 200 200" fill="none" aria-hidden="true">
                <circle cx="100" cy="100" r="90" stroke="#00afef" stroke-width="2"/>
                <circle cx="100" cy="100" r="60" stroke="#00afef" stroke-width="2"/>
                <circle cx="100" cy="100" r="30" fill="#00afef" fill-opacity=".35"/>
            </svg>
            <div class="relative mx-auto max-w-2xl">
                <h2 class="font-display text-3xl font-extrabold sm:text-4xl">Sua ótica ainda não é cliente?</h2>
                <p class="mt-4 text-lg text-white/80">Solicite o cadastro e conheça a tabela de preços e o portal de pedidos da Nova Varonil.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('contato') }}" class="btn btn-light">Solicitar cadastro</a>
                    @if ($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-whats">
                            @include('partials.icon', ['icon' => 'chat', 'class' => 'h-5 w-5'])
                            Falar no WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
