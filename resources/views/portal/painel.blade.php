@extends('layouts.app')

@section('titulo', 'Meu painel | Nova Varonil')

@section('conteudo')

<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <div class="container-x py-14 sm:py-20">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="eyebrow">Portal da ótica</p>
                <h1 class="mt-3 text-3xl font-extrabold text-brand-navy sm:text-4xl">Olá, {{ $otica->nome_fantasia }}</h1>
            </div>
            <form method="POST" action="{{ route('portal.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline">Sair</button>
            </form>
        </div>

        @if ($otica->isPendente())
            <div class="card mt-10 max-w-2xl !border-brand-cyan/30 !bg-white">
                <span class="icon-wrap">@include('partials.icon', ['icon' => 'clock'])</span>
                <h2 class="mt-4 text-xl font-bold text-brand-navy">Cadastro em análise</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">
                    Recebemos os dados da sua ótica e o laboratório está analisando. Assim que for aprovado, o
                    portal de pedidos fica disponível aqui mesmo — não é preciso fazer nada além de aguardar.
                </p>
                <p class="mt-4 text-sm text-slate-600">
                    Alguma urgência? Fale com o laboratório pelo
                    <a href="{{ route('contato', ['assunto' => 'duvida']) }}" class="font-semibold text-brand-purple hover:underline">formulário de contato</a>.
                </p>
            </div>
        @else
            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                <div class="card lg:col-span-2">
                    <span class="icon-wrap">@include('partials.icon', ['icon' => 'check'])</span>
                    <h2 class="mt-4 text-xl font-bold text-brand-navy">Cadastro aprovado</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Você já pode montar pedidos: informe a receita, escolha a lente, os tratamentos e a
                        montagem, e veja o valor calculado na hora.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('portal.pedidos.novo') }}" class="btn btn-primary">Novo pedido</a>
                        <a href="{{ route('portal.pedidos.index') }}" class="btn btn-outline">Meus pedidos</a>
                    </div>
                </div>
                <div class="card">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Sua tabela de preço</h2>
                    <p class="mt-3 text-lg font-bold text-brand-purple">{{ $otica->tabelaPreco?->nome ?? '—' }}</p>
                    <p class="mt-1 text-sm text-slate-600">Valor da montagem: {{ $otica->tabelaPreco ? 'R$ '.number_format((float) $otica->tabelaPreco->valor_montagem, 2, ',', '.') : '—' }}</p>
                </div>
            </div>

            <div class="card mt-6 max-w-2xl">
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Dados cadastrados</h2>
                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">Nome fantasia</dt><dd class="font-semibold">{{ $otica->nome_fantasia }}</dd></div>
                    <div><dt class="text-slate-500">CNPJ</dt><dd class="font-semibold">{{ $otica->cnpj ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">E-mail</dt><dd class="font-semibold">{{ $otica->email }}</dd></div>
                    <div><dt class="text-slate-500">Telefone</dt><dd class="font-semibold">{{ $otica->telefone ?? '—' }}</dd></div>
                </dl>
                <p class="mt-4 text-xs text-slate-500">Precisa corrigir algum dado? Fale com o laboratório pelo contato do site.</p>
            </div>
        @endif
    </div>
</section>

@endsection
