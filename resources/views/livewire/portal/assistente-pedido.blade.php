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
            @foreach (['Cliente', 'Receita', 'Lente', 'Tratamentos', 'Montagem', 'Profissional', 'Resumo'] as $i => $rotulo)
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

                        {{-- Formato ou foto da armação --}}
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Formato da armação (opcional)</p>
                            <p class="mt-1 text-sm text-slate-600">Isso ajuda o laboratório na montagem. Se preferir, mande uma foto em vez de escolher o formato.</p>

                            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                                @foreach (\App\Models\Pedido::FORMATOS_ARMACAO as $chave => $rotulo)
                                    @if ($chave !== \App\Models\Pedido::FORMATO_UPLOAD)
                                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-2xl border-2 p-3 text-center transition
                                            {{ $montagemFormatoArmacao === $chave ? 'border-brand-purple bg-brand-purple/5' : 'border-slate-200 hover:border-brand-purple/40' }}">
                                            <input type="radio" wire:model.live="montagemFormatoArmacao" value="{{ $chave }}" class="sr-only">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-8 w-8 text-brand-navy">
                                                <path d="M2 12c1-3 3-4 5-4s4 1 5 3c1-2 3-3 5-3s4 1 5 4" stroke-linecap="round" stroke-linejoin="round"/>
                                                <circle cx="6.5" cy="13.5" r="3.5"/>
                                                <circle cx="17.5" cy="13.5" r="3.5"/>
                                            </svg>
                                            <span class="text-xs font-semibold text-slate-700">{{ $rotulo }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                            <label class="mt-3 flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition
                                {{ $montagemFormatoArmacao === \App\Models\Pedido::FORMATO_UPLOAD ? 'border-brand-purple bg-brand-purple/5' : 'border-slate-200 hover:border-brand-purple/40' }}">
                                <input type="radio" wire:model.live="montagemFormatoArmacao" value="{{ \App\Models\Pedido::FORMATO_UPLOAD }}" class="h-4 w-4 accent-brand-purple">
                                <span class="font-semibold text-brand-navy">Enviar foto da armação (em vez de escolher o formato)</span>
                            </label>
                            @error('montagemFormatoArmacao') <p class="error-text">{{ $message }}</p> @enderror

                            @if ($montagemFormatoArmacao === \App\Models\Pedido::FORMATO_UPLOAD)
                                <div class="mt-3">
                                    <input type="file" wire:model="montagemFotoArmacao" accept="image/*" class="field">
                                    <div wire:loading wire:target="montagemFotoArmacao" class="mt-1 text-xs text-slate-500">Enviando foto...</div>
                                    @error('montagemFotoArmacao') <p class="error-text">{{ $message }}</p> @enderror
                                    @if ($montagemFotoArmacao)
                                        <img src="{{ $montagemFotoArmacao->temporaryUrl() }}" alt="Foto da armação" class="mt-2 h-24 w-auto rounded-lg border border-slate-200 object-cover">
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Medidas da armação --}}
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Medidas da armação (opcional)</p>
                            <div class="mt-3 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                                <div>
                                    <label for="montagemMva" class="label">MVA (mm)</label>
                                    <input type="number" step="0.5" id="montagemMva" wire:model.live="montagemMva" class="field">
                                    @error('montagemMva') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="montagemMha" class="label">MHA (mm)</label>
                                    <input type="number" step="0.5" id="montagemMha" wire:model.live="montagemMha" class="field">
                                    @error('montagemMha') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="montagemDma" class="label">DMA (mm)</label>
                                    <input type="number" step="0.5" id="montagemDma" wire:model.live="montagemDma" class="field">
                                    @error('montagemDma') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="montagemPonte" class="label">Ponte (mm)</label>
                                    <input type="number" step="0.5" id="montagemPonte" wire:model="montagemPonte" class="field">
                                    @error('montagemPonte') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="montagemDpa" class="label">DPA (mm)</label>
                                    <input type="number" step="0.5" id="montagemDpa" wire:model="montagemDpa" class="field">
                                    @error('montagemDpa') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            @php $diametroEstimado = $this->diametroEstimado; @endphp
                            @if ($diametroEstimado['od'] || $diametroEstimado['oe'])
                                <div class="mt-3 rounded-2xl bg-mist p-4 text-sm">
                                    <p class="font-semibold text-brand-navy">
                                        Diâmetro estimado: O.D. {{ $diametroEstimado['od'] ?? '—' }} mm · O.E. {{ $diametroEstimado['oe'] ?? '—' }} mm
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">Cálculo aproximado a partir da DMA (ou MHA) informada — o laboratório confere antes de montar.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Clip-on e envio da armação --}}
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Armação é clip-on?</p>
                                <div class="mt-2 flex flex-wrap gap-4">
                                    @foreach (\App\Models\Pedido::CLIPON_OPTIONS as $chave => $rotulo)
                                        <label class="flex items-center gap-2 text-sm text-slate-700">
                                            <input type="radio" wire:model="montagemClipon" value="{{ $chave }}" class="accent-brand-purple">
                                            {{ $rotulo }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <label class="flex cursor-pointer items-center gap-3">
                                <input type="checkbox" wire:model="montagemEnviarArmacao" class="h-4 w-4 accent-brand-purple">
                                <span class="text-sm font-semibold text-brand-navy">A armação será enviada para a montagem</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Passo 6: paciente e profissional --}}
            @if ($step === 6)
                <h2 class="text-xl font-bold text-brand-navy">Paciente e profissional</h2>
                <p class="mt-1 text-sm text-slate-600">Dados de quem passou a receita e do paciente (tudo opcional, exceto o tipo).</p>

                <div class="mt-6 space-y-6">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Quem passou a receita?</p>
                        <div class="mt-2 flex flex-wrap gap-4">
                            @foreach (\App\Models\Pedido::TIPOS_PROFISSIONAL as $chave => $rotulo)
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input type="radio" wire:model.live="tipoProfissional" value="{{ $chave }}" class="accent-brand-purple">
                                    {{ $rotulo }}
                                </label>
                            @endforeach
                        </div>
                        @error('tipoProfissional') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="profissionalNome" class="label">
                                Nome d{{ $tipoProfissional === \App\Models\Pedido::TIPO_PROFISSIONAL_OPTOMETRISTA ? 'o optometrista' : 'o médico' }} (opcional)
                            </label>
                            <input type="text" id="profissionalNome" wire:model="profissionalNome" class="field">
                            @error('profissionalNome') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        @if ($tipoProfissional === \App\Models\Pedido::TIPO_PROFISSIONAL_MEDICO)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="profissionalUfCrm" class="label">UF do CRM (opcional)</label>
                                    <select id="profissionalUfCrm" wire:model="profissionalUfCrm" class="field">
                                        <option value="">—</option>
                                        @foreach (\App\Models\Pedido::UFS_BRASIL as $uf)
                                            <option value="{{ $uf }}">{{ $uf }}</option>
                                        @endforeach
                                    </select>
                                    @error('profissionalUfCrm') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="profissionalCrm" class="label">CRM (opcional)</label>
                                    <input type="text" id="profissionalCrm" wire:model="profissionalCrm" class="field">
                                    @error('profissionalCrm') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wider text-brand-navy">Dados do paciente (opcional)</p>
                        <div class="mt-3 grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="pacienteIniciais" class="label">Iniciais</label>
                                <input type="text" id="pacienteIniciais" wire:model="pacienteIniciais" class="field" maxlength="10">
                                @error('pacienteIniciais') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="pacienteIdade" class="label">Idade</label>
                                <input type="number" step="1" min="0" max="120" id="pacienteIdade" wire:model="pacienteIdade" class="field">
                                @error('pacienteIdade') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="pacienteComplemento" class="label">Complemento</label>
                                <input type="text" id="pacienteComplemento" wire:model="pacienteComplemento" class="field">
                                @error('pacienteComplemento') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Passo 7: resumo --}}
            @if ($step === 7)
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
                    @if ($comMontagem && $montagemFormatoArmacao)
                        <div>
                            <dt class="text-slate-500">Armação</dt>
                            <dd class="font-semibold">
                                {{ \App\Models\Pedido::FORMATOS_ARMACAO[$montagemFormatoArmacao] ?? $montagemFormatoArmacao }}
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-slate-500">Receita passada por</dt>
                        <dd class="font-semibold">
                            {{ \App\Models\Pedido::TIPOS_PROFISSIONAL[$tipoProfissional] ?? $tipoProfissional }}
                            @if ($profissionalNome) — {{ $profissionalNome }} @endif
                            @if ($tipoProfissional === \App\Models\Pedido::TIPO_PROFISSIONAL_MEDICO && ($profissionalUfCrm || $profissionalCrm))
                                (CRM {{ $profissionalUfCrm }} {{ $profissionalCrm }})
                            @endif
                        </dd>
                    </div>
                    @if ($pacienteIniciais || $pacienteIdade || $pacienteComplemento)
                        <div>
                            <dt class="text-slate-500">Paciente</dt>
                            <dd class="font-semibold">
                                {{ collect([$pacienteIniciais, $pacienteIdade ? $pacienteIdade.' anos' : null, $pacienteComplemento])->filter()->join(' · ') }}
                            </dd>
                        </div>
                    @endif
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

                @if ($step < 7)
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
