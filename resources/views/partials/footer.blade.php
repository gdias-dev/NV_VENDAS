<footer class="mt-auto border-t border-line bg-mist">
    <div class="brand-bar"></div>
    <div class="container-x grid gap-10 py-14 md:grid-cols-12">
        <div class="md:col-span-5">
            <img src="{{ asset('img/logo.png') }}" alt="Nova Varonil Laboratório Óptico desde 1964" width="1200" height="240" class="h-10 w-auto" loading="lazy">
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-600">
                Laboratório óptico no Centro do Rio de Janeiro. Lentes com ou sem montagem para óticas, com a tradição de quem faz desde {{ config('nova.fundacao') }}.
            </p>
            @if (config('nova.instagram'))
                <a href="{{ config('nova.instagram') }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-navy hover:text-brand-purple">
                    @include('partials.icon', ['icon' => 'instagram', 'class' => 'h-5 w-5'])
                    @@novavaronil
                </a>
            @endif
        </div>

        <div class="md:col-span-3">
            <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Navegação</h2>
            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-purple" href="{{ route('home') }}">Início</a></li>
                <li><a class="hover:text-brand-purple" href="{{ route('lentes') }}">Lentes e tratamentos</a></li>
                <li><a class="hover:text-brand-purple" href="{{ route('como-pedir') }}">Como pedir</a></li>
                <li><a class="hover:text-brand-purple" href="{{ route('sobre') }}">Sobre o laboratório</a></li>
                <li><a class="hover:text-brand-purple" href="{{ route('contato') }}">Contato</a></li>
                <li><a class="hover:text-brand-purple" href="{{ route('area-oticas') }}">Área das óticas</a></li>
            </ul>
        </div>

        <div class="md:col-span-4">
            <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Fale conosco</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-600">
                <li class="flex gap-3">
                    @include('partials.icon', ['icon' => 'pin', 'class' => 'mt-0.5 h-5 w-5 shrink-0 text-brand-purple'])
                    <span>{{ config('nova.endereco') }}</span>
                </li>
                @if (config('nova.telefone'))
                    <li class="flex gap-3">
                        @include('partials.icon', ['icon' => 'phone', 'class' => 'mt-0.5 h-5 w-5 shrink-0 text-brand-purple'])
                        <a class="hover:text-brand-purple" href="tel:{{ preg_replace('/\D/', '', config('nova.telefone')) }}">{{ config('nova.telefone') }}</a>
                    </li>
                @endif
                @if (config('nova.email_contato'))
                    <li class="flex gap-3">
                        @include('partials.icon', ['icon' => 'mail', 'class' => 'mt-0.5 h-5 w-5 shrink-0 text-brand-purple'])
                        <a class="break-all hover:text-brand-purple" href="mailto:{{ config('nova.email_contato') }}">{{ config('nova.email_contato') }}</a>
                    </li>
                @endif
                <li class="flex gap-3">
                    @include('partials.icon', ['icon' => 'clock', 'class' => 'mt-0.5 h-5 w-5 shrink-0 text-brand-purple'])
                    <span>{{ config('nova.horario') }}</span>
                </li>
            </ul>
            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-whats mt-5">
                    @include('partials.icon', ['icon' => 'chat', 'class' => 'h-5 w-5'])
                    Chamar no WhatsApp
                </a>
            @endif
        </div>
    </div>

    <div class="border-t border-line">
        <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-xs text-slate-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} Nova Varonil Laboratório Óptico. Todos os direitos reservados.</p>
            <p>Desde {{ config('nova.fundacao') }} no Centro do Rio de Janeiro.</p>
        </div>
    </div>
</footer>
