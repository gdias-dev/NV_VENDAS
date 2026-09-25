@extends('layouts.app')

@section('titulo', 'Contato | Nova Varonil')
@section('descricao', 'Fale com o laboratório Nova Varonil: solicite o cadastro da sua ótica, tire dúvidas ou peça um orçamento.')

@section('conteudo')

@include('partials.page-header', [
    'titulo' => 'Contato',
    'subtitulo' => 'Solicite o cadastro da sua ótica, tire dúvidas sobre receitas ou peça um orçamento.',
])

<section class="section">
    <div class="container-x grid gap-10 lg:grid-cols-12">

        {{-- Formulário --}}
        <div class="lg:col-span-7">
            @if (session('sucesso'))
                <div class="mb-6 flex gap-3 rounded-2xl border border-brand-green/30 bg-brand-green/10 p-5 text-sm text-brand-green" role="status">
                    @include('partials.icon', ['icon' => 'check', 'class' => 'mt-0.5 h-5 w-5 shrink-0'])
                    <p><strong>Mensagem enviada!</strong> Recebemos o seu contato e responderemos em breve.</p>
                </div>
            @endif

            @if (session('erro'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-medium text-red-700" role="alert">{{ session('erro') }}</div>
            @endif

            <form id="form-contato" method="POST" action="{{ route('contato.enviar') }}" class="card space-y-5 !p-6 sm:!p-8" novalidate>
                @csrf

                {{-- Armadilha para robôs: deve ficar vazio --}}
                <div class="absolute -left-[9999px]" aria-hidden="true">
                    <label for="website">Não preencha este campo</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nome" class="label">Seu nome *</label>
                        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="field" autocomplete="name" required>
                        @error('nome') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="otica" class="label">Nome da ótica</label>
                        <input type="text" id="otica" name="otica" value="{{ old('otica') }}" class="field" autocomplete="organization">
                        @error('otica') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="email" class="label">E-mail *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="field" autocomplete="email" required>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="telefone" class="label">Telefone / WhatsApp</label>
                        <input type="tel" id="telefone" name="telefone" value="{{ old('telefone') }}" class="field" autocomplete="tel" placeholder="(21) 90000-0000">
                        @error('telefone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="assunto" class="label">Assunto *</label>
                    <select id="assunto" name="assunto" class="field" required>
                        @foreach (\App\Mail\ContatoRecebido::ASSUNTOS as $valor => $texto)
                            <option value="{{ $valor }}" @selected(old('assunto', request('assunto', 'cadastro')) === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('assunto') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mensagem" class="label">Mensagem *</label>
                    <textarea id="mensagem" name="mensagem" rows="5" class="field" required>{{ old('mensagem') }}</textarea>
                    @error('mensagem') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    Enviar mensagem
                    @include('partials.icon', ['icon' => 'arrow', 'class' => 'h-4 w-4'])
                </button>
                <p class="text-xs text-slate-500">Usamos seus dados apenas para responder ao seu contato.</p>
            </form>
        </div>

        {{-- Informações --}}
        <aside class="space-y-5 lg:col-span-5">
            <div class="card">
                <h2 class="text-lg font-bold text-brand-navy">Informações</h2>
                <ul class="mt-5 space-y-4 text-sm text-slate-600">
                    <li class="flex gap-3">
                        <span class="icon-wrap !h-10 !w-10 shrink-0">@include('partials.icon', ['icon' => 'pin', 'class' => 'h-5 w-5'])</span>
                        <div><p class="font-semibold text-ink">Endereço</p><p>{{ config('nova.endereco') }}</p>
                            @if (config('nova.mapa_url'))
                                <a href="{{ config('nova.mapa_url') }}" target="_blank" rel="noopener" class="font-semibold text-brand-purple hover:underline">Ver no mapa</a>
                            @endif
                        </div>
                    </li>
                    @if (config('nova.telefone'))
                        <li class="flex gap-3">
                            <span class="icon-wrap !h-10 !w-10 shrink-0">@include('partials.icon', ['icon' => 'phone', 'class' => 'h-5 w-5'])</span>
                            <div><p class="font-semibold text-ink">Telefone</p><a class="hover:text-brand-purple" href="tel:{{ preg_replace('/\D/', '', config('nova.telefone')) }}">{{ config('nova.telefone') }}</a></div>
                        </li>
                    @endif
                    @if (config('nova.email_contato'))
                        <li class="flex gap-3">
                            <span class="icon-wrap !h-10 !w-10 shrink-0">@include('partials.icon', ['icon' => 'mail', 'class' => 'h-5 w-5'])</span>
                            <div><p class="font-semibold text-ink">E-mail</p><a class="break-all hover:text-brand-purple" href="mailto:{{ config('nova.email_contato') }}">{{ config('nova.email_contato') }}</a></div>
                        </li>
                    @endif
                    <li class="flex gap-3">
                        <span class="icon-wrap !h-10 !w-10 shrink-0">@include('partials.icon', ['icon' => 'clock', 'class' => 'h-5 w-5'])</span>
                        <div><p class="font-semibold text-ink">Atendimento</p><p>{{ config('nova.horario') }}</p></div>
                    </li>
                </ul>
            </div>

            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-whats w-full !py-4">
                    @include('partials.icon', ['icon' => 'chat', 'class' => 'h-5 w-5'])
                    Prefere WhatsApp? Chame agora
                </a>
            @endif
        </aside>
    </div>
</section>

@endsection
