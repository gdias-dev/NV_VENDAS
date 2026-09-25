@extends('layouts.app')

@section('titulo', 'Página não encontrada | Nova Varonil')

@section('conteudo')
<section class="container-x py-24 text-center">
    <p class="font-display text-7xl font-extrabold text-brand-cyan">404</p>
    <h1 class="mt-4 text-3xl font-extrabold text-brand-navy">Página não encontrada</h1>
    <p class="lead mx-auto mt-3 max-w-md">O endereço que você acessou não existe ou foi movido.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-8">Voltar ao início</a>
</section>
@endsection
