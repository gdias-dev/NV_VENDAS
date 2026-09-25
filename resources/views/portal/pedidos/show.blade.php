@extends('layouts.app')

@section('titulo', 'Pedido #'.$pedido->id.' | Nova Varonil')

@section('conteudo')

<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <div class="container-x py-14 sm:py-20">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="eyebrow">Portal da ótica</p>
                <h1 class="mt-3 text-3xl font-extrabold text-brand-navy sm:text-4xl">Pedido #{{ $pedido->id }}</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('portal.pedidos.pdf', $pedido) }}" target="_blank" class="btn btn-outline">Ordem de serviço (PDF)</a>
                <a href="{{ route('portal.pedidos.index') }}" class="btn btn-outline">Voltar para meus pedidos</a>
            </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="card lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Status</h2>
                    <span @class([
                        'rounded-full px-3 py-1 text-xs font-bold',
                        'bg-emerald-100 text-emerald-700' => $pedido->status === \App\Models\Pedido::STATUS_ENTREGUE,
                        'bg-red-100 text-red-700' => $pedido->status === \App\Models\Pedido::STATUS_CANCELADO,
                        'bg-amber-100 text-amber-700' => ! in_array($pedido->status, [\App\Models\Pedido::STATUS_ENTREGUE, \App\Models\Pedido::STATUS_CANCELADO]),
                    ])>{{ $pedido->statusLabel() }}</span>
                </div>
                <p class="mt-2 text-sm text-slate-500">Pedido feito em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</p>

                <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">Cliente</dt><dd class="font-semibold">{{ $pedido->cliente_nome }}</dd></div>
                    <div><dt class="text-slate-500">Telefone</dt><dd class="font-semibold">{{ $pedido->cliente_telefone ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Lente</dt><dd class="font-semibold">{{ $pedido->lente_nome ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Montagem</dt><dd class="font-semibold">{{ $pedido->com_montagem ? 'Sim' : 'Não' }}</dd></div>
                </dl>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-mist p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Olho direito (OD)</p>
                        <p class="mt-2 text-sm text-slate-600">
                            Esf. {{ $pedido->od_esferico ?? '—' }} · Cil. {{ $pedido->od_cilindrico ?? '—' }} ·
                            Eixo {{ $pedido->od_eixo ?? '—' }} · Ad. {{ $pedido->od_adicao ?? '—' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-mist p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Olho esquerdo (OE)</p>
                        <p class="mt-2 text-sm text-slate-600">
                            Esf. {{ $pedido->oe_esferico ?? '—' }} · Cil. {{ $pedido->oe_cilindrico ?? '—' }} ·
                            Eixo {{ $pedido->oe_eixo ?? '—' }} · Ad. {{ $pedido->oe_adicao ?? '—' }}
                        </p>
                    </div>
                </div>

                @if ($pedido->tratamentos->isNotEmpty())
                    <div class="mt-6">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Tratamentos</p>
                        <ul class="mt-2 space-y-1 text-sm text-slate-600">
                            @foreach ($pedido->tratamentos as $tratamento)
                                <li>{{ $tratamento->tratamento_nome }} — R$ {{ number_format((float) $tratamento->preco, 2, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($pedido->montagem_observacoes)
                    <div class="mt-6">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Observações da montagem</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $pedido->montagem_observacoes }}</p>
                    </div>
                @endif

                @if ($pedido->temDadosArmacao())
                    <div class="mt-6">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Armação</p>
                        <div class="mt-2 grid gap-4 sm:grid-cols-2">
                            <dl class="space-y-1 text-sm text-slate-600">
                                @if ($pedido->montagem_formato_armacao && $pedido->montagem_formato_armacao !== \App\Models\Pedido::FORMATO_UPLOAD)
                                    <div><span class="text-slate-500">Formato:</span> {{ $pedido->formatoArmacaoLabel() }}</div>
                                @endif
                                @if ($pedido->montagem_mva)
                                    <div><span class="text-slate-500">MVA:</span> {{ $pedido->montagem_mva }} mm</div>
                                @endif
                                @if ($pedido->montagem_mha)
                                    <div><span class="text-slate-500">MHA:</span> {{ $pedido->montagem_mha }} mm</div>
                                @endif
                                @if ($pedido->montagem_dma)
                                    <div><span class="text-slate-500">DMA:</span> {{ $pedido->montagem_dma }} mm</div>
                                @endif
                                @if ($pedido->montagem_ponte)
                                    <div><span class="text-slate-500">Ponte:</span> {{ $pedido->montagem_ponte }} mm</div>
                                @endif
                                @if ($pedido->montagem_dpa)
                                    <div><span class="text-slate-500">DPA:</span> {{ $pedido->montagem_dpa }} mm</div>
                                @endif
                                @if ($pedido->montagem_diametro_od || $pedido->montagem_diametro_oe)
                                    <div><span class="text-slate-500">Diâmetro estimado:</span> O.D. {{ $pedido->montagem_diametro_od ?? '—' }} mm · O.E. {{ $pedido->montagem_diametro_oe ?? '—' }} mm</div>
                                @endif
                                @if ($pedido->montagem_clipon)
                                    <div><span class="text-slate-500">Clip-on:</span> {{ $pedido->cliponLabel() }}</div>
                                @endif
                                <div><span class="text-slate-500">Armação enviada para montagem:</span> {{ $pedido->montagem_enviar_armacao ? 'Sim' : 'Não' }}</div>
                            </dl>
                            @if ($pedido->montagem_foto_armacao)
                                <img src="{{ asset('storage/'.$pedido->montagem_foto_armacao) }}" alt="Foto da armação" class="h-32 w-auto rounded-lg border border-slate-200 object-cover">
                            @endif
                        </div>
                    </div>
                @endif

                @if ($pedido->observacoes)
                    <div class="mt-6">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Observações do laboratório</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $pedido->observacoes }}</p>
                    </div>
                @endif
            </div>

            <div class="card h-fit">
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-brand-navy">Valores</h2>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Lente (OD)</span><span>R$ {{ number_format((float) $pedido->preco_lente_od, 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Lente (OE)</span><span>R$ {{ number_format((float) $pedido->preco_lente_oe, 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Tratamentos</span><span>R$ {{ number_format((float) $pedido->preco_tratamentos, 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Montagem</span><span>R$ {{ number_format((float) $pedido->preco_montagem, 2, ',', '.') }}</span></div>
                    <div class="mt-2 flex justify-between border-t border-slate-200 pt-2 text-base font-extrabold text-brand-navy">
                        <span>Total</span><span>R$ {{ number_format((float) $pedido->preco_total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
