@extends('layouts.app')

@section('titulo', 'Área das óticas | Nova Varonil')
@section('descricao', 'Portal de pedidos de lentes da Nova Varonil para óticas parceiras.')

@section('conteudo')

<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <div class="container-x py-16 sm:py-24">
        <div class="mx-auto max-w-xl text-center">
            <span class="icon-wrap mx-auto !h-16 !w-16 !rounded-2xl">@include('partials.icon', ['icon' => 'lock', 'class' => 'h-8 w-8'])</span>
            <p class="eyebrow mt-6 justify-center">Portal das óticas</p>
            <h1 class="mt-3 text-4xl font-extrabold text-brand-navy">Em breve por aqui</h1>
            <p class="lead mt-4">
                Estamos preparando o portal onde a sua ótica fará pedidos, consultará a tabela de preços e acompanhará cada entrega.
            </p>
        </div>

        <div class="mx-auto mt-10 grid max-w-3xl gap-4 sm:grid-cols-3">
            @foreach ([
                ['clipboard', 'Pedidos online', 'Receita, lente, tratamentos e montagem em poucos passos.'],
                ['chart', 'Tabela de preços', 'Valores atualizados e cálculo automático do pedido.'],
                ['truck', 'Acompanhamento', 'Status de cada pedido até a expedição.'],
            ] as $r)
                <div class="card text-center">
                    <span class="icon-wrap mx-auto">@include('partials.icon', ['icon' => $r[0]])</span>
                    <h2 class="mt-4 text-base font-bold text-brand-navy">{{ $r[1] }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $r[2] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('contato', ['assunto' => 'cadastro']) }}" class="btn btn-primary">Avisem-me quando estiver no ar</a>
            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-whats">Falar no WhatsApp</a>
            @endif
        </div>
    </div>
</section>

@endsection
