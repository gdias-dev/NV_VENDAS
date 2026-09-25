@extends('layouts.app')

@section('titulo', 'Novo pedido | Nova Varonil')

@section('conteudo')

<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <div class="container-x py-14 sm:py-20">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="eyebrow">Portal da ótica</p>
                <h1 class="mt-3 text-3xl font-extrabold text-brand-navy sm:text-4xl">Novo pedido</h1>
            </div>
            <a href="{{ route('portal.pedidos.index') }}" class="btn btn-outline">Meus pedidos</a>
        </div>

        <div class="mx-auto mt-10 max-w-3xl">
            @livewire('portal.assistente-pedido')
        </div>
    </div>
</section>

@endsection
