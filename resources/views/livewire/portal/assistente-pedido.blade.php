<div>
    @if ($pedidoCriado)
        <div class="card !border-brand-cyan/30 !bg-white text-center">
            <span class="icon-wrap mx-auto">@include('partials.icon', ['icon' => 'check'])</span>
            <h2 class="mt-4 text-xl font-bold text-brand-navy">Pedido #{{ $pedidoId }} enviado!</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                O laboratório já recebeu o pedido e vai acompanhar a produção. Você pode ver o andamento a
                qualquer momento em "Meus pedidos".
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('portal.pedidos.show', $pedidoId) }}" class="btn btn-primary">Ver pedido</a>
                <a href="{{ route('portal.pedidos.pdf', $pedidoId) }}" target="_blank" class="btn btn-outline">Ordem de serviço (PDF)</a>
                <a href="{{ route('portal.pedidos.novo') }}" class="btn btn-outline" wire:navigate>Fazer outro pedido</a>
            </div>
        </div>
    @else
        {{-- Indicador de progresso --}}
        <div class="mb-8 flex items-center justify-between gap-2">
            @foreach (['Cliente', 'Receita', 'Lente', 'Tratamentos', 'Montagem', 'Resumo'] as $i => $rotulo)
                @php $numero = $i + 1; @endphp
                <button
                    type="button"
                    wire:click="irParaPasso({{ $numero }})"
                    @if ($numero >= $step) disabled @endif
                    class="flex flex-1 flex-col items-center gap-1 text-center {{ $numero >= $step ? 'cursor-default' : 'cursor-pointer' }}"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold
                        {{ $numero < $step ? 'bg-brand-green text-white' : ($numero === $step ? 'bg-brand-purple text-white' : 'bg-slate-200 text-slate-500') }}">
                        {{ $numero < $step ? '✓' : $numero }}
                    </span>
                    <span class="hidden text-xs font-semibold text-slate-500 sm:block">{{ $rotulo }}</span>
                </button>
            @endforeach
        </div>

        <div class="card !p-6 sm:!p-8">
            {{-- Passo 1: identificação --}}
            @if ($step === 1)
                <h2 class="text-xl font-bold text-brand-navy">Quem é o cliente?</h2>
                <p class="mt-1 text-sm text-slate-600">Só para identificar o pedido — não precisa de cadastro dele.</p>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="clienteNome" class="label">Nome do cliente *</label>
                        <input type="text" id="clienteNome" wire:model="clienteNome" class="field" autofocus>
                        @error('clienteNome') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="clienteTelefone" class="label">Telefone (opcional)</label>
                        <input type="tel" id="clienteTelefone" wire:model="clienteTelefone" class="field">
                        @error('clienteTelefone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Passo 2: receita --}}
            @if ($step === 2)
                <h2 class="text-xl font-bold text-brand-navy">Receita</h2>
                <p class="mt-1 text-sm text-slate-600">Preencha o que constar na receita. Deixe em branco o que não se aplicar.</p>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="mb-3 text-sm font-extrabold uppercase tracking-wider text-brand-purple">Olho direito (OD)</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="odEsferico" class="label">Esférico</label>
                                <input type="number" step="0.25" id="odEsferico" wire:model="odEsferico" class="field">
                                @error('odEsferico') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="odCilindrico" class="label">Cilíndrico</label>
                                <input type="number" step="0.25" id="odCilindrico" wire:model="odCilindrico" class="field">
                                @error('odCilindrico') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="odEixo" class="label">Eixo</label>
                                <input type="number" step="1" id="odEixo" wire:model="odEixo" class="field">
                                @error('odEixo') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="odAdicao" class="label">Adição</label>
                                <input type="number" step="0.25" id="odAdicao" wire:model="odAdicao" class="field">
                                @error('odAdicao') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="mb-3 text-sm font-extrabold uppercase tracking-wider text-brand-purple">Olho esquerdo (OE)</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="oeEsferico" class="label">Esférico</label>
                                <input type="number" step="0.25" id="oeEsferico" wire:model="oeEsferico" class="field">
                                @error('oeEsferico') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="oeCilindrico" class="label">Cilíndrico</label>
                                <input type="number" step="0.25" id="oeCilindrico" wire:model="oeCilindrico" class="field">
                                @error('oeCilindrico') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="oeEixo" class="label">Eixo</label>
                                <input type="number" step="1" id="oeEixo" wire:model="oeEixo" class="field">
                                @error('oeEixo') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="oeAdicao" class="label">Adição</label>
                                <input type="number" step="0.25" id="oeAdicao" wire:model="oeAdicao" class="field">
                                @error('oeAdicao') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Passo 3: lente --}}
            @if ($step === 3)
                <h2 class="text-xl font-bold text-brand-navy">Escolha a lente</h2>
                <p class="mt-1 text-sm text-slate-600">O preço é calculado automaticamente pela faixa de grau da receita.</p>

                <div class="mt-6 space-y-3">
                    @foreach ($this->lentesDisponiveis as $lente)
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 p-4 transition
                            {{ $lenteId === $lente->id ? 'border-brand-purple bg-brand-purple/5' : 'border-slate-200 hover:border-brand-purple/40' }}">
                            <input type="radio" wire:model="lenteId" value="{{ $lente->id }}" class="h-4 w-4 accent-brand-purple">
                            <span>
                                <span class="block font-bold text-brand-navy">{{ $lente->nome }}</span>
                                <span class="block text-sm text-slate-600">{{ collect([$lente->material, $lente->indice ? 'índice '.$lente->indice : null])->filter()->join(' · ') }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('lenteId') <p class="error-text mt-2">{{ $message }}</p> @enderror
            @endif

            {{-- Passo 4: tratamentos --}}
            @if ($step === 4)
                <h2 class="text-xl font-bold text-brand-navy">Tratamentos (opcional)</h2>
                <p class="mt-1 text-sm text-slate-600">Marque quantos quiser.</p>

                <div class="mt-6 space-y-3">
                    @forelse ($this->tratamentosDisponiveis as $tratamento)
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 p-4 transition
                            {{ in_array($tratamento->id, $tratamentosSelecionados) ? 'border-brand-purple bg-brand-purple/5' : 'border-slate-200 hover:border-brand-purple/40' }}">
                            <input type="checkbox" wire:model="tratamentosSelecionados" value="{{ $tratamento->id }}" class="h-4 w-4 accent-brand-purple">
                            <span>
                                <span class="block font-bold text-brand-navy">{{ $tratamento->nome }}</span>
                                @if ($tratamento->descricao)
                                    <span class="block text-sm text-slate-600">{{ $tratamento->descricao }}</span>
                                @endif
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum tratamento disponível no momento.</p>
                    @endforelse
                </div>
            @endif

            {{-- Passo 5: montagem --}}
            @if ($step === 5)
                <h2 class="text-xl font-bold text-brand-navy">Montagem</h2>
                <p class="mt-1 text-sm text-slate-600">O laboratório pode montar a lente na armação do cliente.</p>

                <div class="mt-6 space-y-4">
                    <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 p-4 transition
                        {{ $comMontagem ? 'border-brand-purple bg-brand-purple/5' : 'border-slate-200 hover:border-brand-purple/40' }}">
                        <input type="checkbox" wire:model.live="comMontagem" class="h-4 w-4 accent-brand-purple">
                        <span class="font-bold text-brand-navy">Quero montagem</span>
                    </label>

                    @if ($comMontagem)
                        <div>
                            <label for="montagemObservacoes" class="label">Observações da montagem (opcional)</label>
                            <textarea id="montagemObservacoes" wire:model="montagemObservacoes" rows="3" class="field" placeholder="Ex.: tipo de armação, medidas, alguma observação para a produção..."></textarea>
                            @error('montagemObservacoes') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            @endif

            {{-- Passo 6: resumo --}}
            @if ($step === 6)
                <h2 class="text-xl font-bold text-brand-navy">Resumo do pedido</h2>
                <p class="mt-1 text-sm text-slate-600">Confira os dados antes de confirmar.</p>

                @php $resumo = $this->resumo; @endphp

                <dl class="mt-6 grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">Cliente</dt><dd class="font-semibold">{{ $clienteNome }}</dd></div>
                    <div><dt class="text-slate-500">Lente</dt><dd class="font-semibold">{{ $this->lenteSelecionada?->nome ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">OD</dt><dd class="font-semibold">Esf. {{ $odEsferico ?: '0' }} / Cil. {{ $odCilindrico ?: '0' }} / Eixo {{ $odEixo ?: '—' }} / Ad. {{ $odAdicao ?: '—' }}</dd></div>
                    <div><dt class="text-slate-500">OE</dt><dd class="font-semibold">Esf. {{ $oeEsferico ?: '0' }} / Cil. {{ $oeCilindrico ?: '0' }} / Eixo {{ $oeEixo ?: '—' }} / Ad. {{ $oeAdicao ?: '—' }}</dd></div>
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Tratamentos</dt>
                        <dd class="font-semibold">{{ collect($resumo['tratamentos'])->pluck('nome')->join(', ') ?: 'Nenhum' }}</dd>
                    </div>
                    <div><dt class="text-slate-500">Montagem</dt><dd class="font-semibold">{{ $comMontagem ? 'Sim' : 'Não' }}</dd></div>
                </dl>

                <div class="mt-6 space-y-2 rounded-2xl bg-mist p-5 text-sm">
                    <div class="flex justify-between"><span>Lente (OD)</span><span>R$ {{ number_format($resumo['preco_lente_od'], 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Lente (OE)</span><span>R$ {{ number_format($resumo['preco_lente_oe'], 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Tratamentos</span><span>R$ {{ number_format($resumo['preco_tratamentos'], 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Montagem</span><span>R$ {{ number_format($resumo['preco_montagem'], 2, ',', '.') }}</span></div>
                    <div class="mt-2 flex justify-between border-t border-slate-200 pt-2 text-base font-extrabold text-brand-navy">
                        <span>Total</span><span>R$ {{ number_format($resumo['preco_total'], 2, ',', '.') }}</span>
                    </div>
                </div>

                @if (($resumo['preco_lente_od'] <= 0 && $odEsferico) || ($resumo['preco_lente_oe'] <= 0 && $oeEsferico))
                    <p class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                        Não encontramos uma faixa de preço cadastrada para essa combinação de grau e lente. O pedido
                        pode ser enviado mesmo assim — o laboratório vai confirmar o valor com você.
                    </p>
                @endif
            @endif

            {{-- Navegação --}}
            <div class="mt-8 flex items-center justify-between gap-3 border-t border-slate-100 pt-6">
                @if ($step > 1)
                    <button type="button" wire:click="voltar" class="btn btn-outline">Voltar</button>
                @else
                    <span></span>
                @endif

                @if ($step < 6)
                    <button type="button" wire:click="proximo" class="btn btn-primary">Continuar</button>
                @else
                    <button type="button" wire:click="confirmar" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="confirmar">Confirmar pedido</span>
                        <span wire:loading wire:target="confirmar">Enviando...</span>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
