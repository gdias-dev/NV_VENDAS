@extends('layouts.app')

@section('titulo', 'Área das óticas | Nova Varonil')
@section('descricao', 'Acesse o portal de pedidos da sua ótica parceira Nova Varonil.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Área das óticas',
    'subtitulo' => 'Entre com o e-mail e a senha da sua ótica.',
])

<section class="section">
    <div class="container-x">
        <div class="mx-auto max-w-md">

            @if (session('cadastro_enviado'))
                <div class="mb-6 flex gap-3 rounded-2xl border border-brand-green/30 bg-brand-green/10 p-5 text-sm text-brand-green" role="status">
                    @include('partials.icon', ['icon' => 'check', 'class' => 'mt-0.5 h-5 w-5 shrink-0'])
                    <p><strong>Cadastro enviado!</strong> Assim que o laboratório aprovar, você poderá entrar por aqui.</p>
                </div>
            @endif

            @if (session('erro'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-medium text-red-700" role="alert">{{ session('erro') }}</div>
            @endif

            <form method="POST" action="{{ route('portal.login') }}" class="card space-y-5 !p-6 sm:!p-8" novalidate>
                @csrf

                <div>
                    <label for="email" class="label">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="field" autocomplete="username" required autofocus>
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">Senha</label>
                    <input type="password" id="password" name="password" class="field" autocomplete="current-password" required>
                    @error('password') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="lembrar" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-navy focus:ring-brand-cyan">
                    Manter conectado neste computador
                </label>

                <button type="submit" class="btn btn-primary w-full">
                    Entrar
                    @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])
                </button>
            </form>

            <div class="mt-6 space-y-2 text-center text-sm text-slate-600">
                <p>Sua ótica ainda não tem acesso? <a href="{{ route('portal.cadastro') }}" class="font-semibold text-brand-purple hover:underline">Solicite o cadastro</a></p>
                <p>Esqueceu a senha? <a href="{{ route('contato', ['assunto' => 'duvida']) }}" class="font-semibold text-brand-purple hover:underline">Fale com o laboratório</a></p>
            </div>
        </div>
    </div>
</section>

@endsection
