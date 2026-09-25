@extends('layouts.app')

@section('titulo', 'Cadastro da ótica | Nova Varonil')
@section('descricao', 'Solicite o cadastro da sua ótica para pedir lentes pelo portal da Nova Varonil.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Cadastro da sua ótica',
    'subtitulo' => 'Preencha os dados abaixo. Após o envio, o laboratório analisa e libera o seu acesso.',
])

<section class="section">
    <div class="container-x">
        <div class="mx-auto max-w-2xl">

            @if (session('erro'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-medium text-red-700" role="alert">{{ session('erro') }}</div>
            @endif

            <form method="POST" action="{{ route('portal.cadastro.enviar') }}" class="card space-y-5 !p-6 sm:!p-8" novalidate>
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nome_fantasia" class="label">Nome fantasia *</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}" class="field" required autofocus>
                        @error('nome_fantasia') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="razao_social" class="label">Razão social</label>
                        <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}" class="field">
                        @error('razao_social') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="cnpj" class="label">CNPJ</label>
                        <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" class="field" placeholder="00.000.000/0000-00">
                        @error('cnpj') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="telefone" class="label">Telefone / WhatsApp</label>
                        <input type="tel" id="telefone" name="telefone" value="{{ old('telefone') }}" class="field" placeholder="(21) 90000-0000">
                        @error('telefone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="endereco" class="label">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}" class="field">
                    @error('endereco') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">E-mail de acesso *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="field" autocomplete="username" required>
                    <p class="mt-1 text-xs text-slate-500">É com este e-mail que você vai entrar no portal.</p>
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="label">Senha *</label>
                        <input type="password" id="password" name="password" class="field" autocomplete="new-password" required minlength="8">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Confirmar senha *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="field" autocomplete="new-password" required minlength="8">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    Enviar cadastro
                    @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])
                </button>
                <p class="text-xs text-slate-500">Seus dados são usados apenas para a relação comercial com o laboratório.</p>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Já tem acesso? <a href="{{ route('area-oticas') }}" class="font-semibold text-brand-purple hover:underline">Entrar</a>
            </p>
        </div>
    </div>
</section>

@endsection
