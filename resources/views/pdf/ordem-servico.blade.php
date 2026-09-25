<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Ordem de serviço #{{ $pedido->id }}</title>
    <style>
        @page { margin: 28px 32px; }

        body {
            font-family: "Helvetica", "Arial", sans-serif;
            color: #201e1e;
            font-size: 12px;
        }

        .cabecalho {
            border-bottom: 3px solid #12306d;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .cabecalho .nome {
            font-size: 20px;
            font-weight: bold;
            color: #12306d;
        }

        .cabecalho .subtitulo {
            font-size: 11px;
            color: #64748b;
        }

        .titulo-os {
            text-align: right;
        }

        .titulo-os .numero {
            font-size: 18px;
            font-weight: bold;
            color: #3e4095;
        }

        .titulo-os .data {
            font-size: 11px;
            color: #64748b;
        }

        table.cabecalho-tabela {
            width: 100%;
            border-collapse: collapse;
        }

        table.cabecalho-tabela td {
            vertical-align: top;
        }

        .secao {
            margin-top: 16px;
        }

        .secao-titulo {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #12306d;
            border-bottom: 1px solid #d8dee8;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        table.dados {
            width: 100%;
            border-collapse: collapse;
        }

        table.dados td {
            padding: 4px 6px 4px 0;
            vertical-align: top;
        }

        table.dados td.rotulo {
            width: 130px;
            color: #64748b;
        }

        table.receita {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.receita th, table.receita td {
            border: 1px solid #d8dee8;
            padding: 6px 8px;
            text-align: center;
            font-size: 11px;
        }

        table.receita th {
            background: #f4f8fc;
            color: #12306d;
        }

        table.valores {
            width: 60%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-left: auto;
        }

        table.valores td {
            padding: 4px 6px;
        }

        table.valores td.valor {
            text-align: right;
        }

        table.valores tr.total td {
            border-top: 1px solid #12306d;
            font-weight: bold;
            color: #12306d;
            font-size: 13px;
            padding-top: 8px;
        }

        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            background: #f4f8fc;
            color: #12306d;
        }

        .rodape {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #d8dee8;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="cabecalho">
        <table class="cabecalho-tabela">
            <tr>
                <td>
                    <div class="nome">{{ config('nova.nome') }} {{ config('nova.subtitulo') }}</div>
                    <div class="subtitulo">
                        Desde {{ config('nova.fundacao') }} · {{ config('nova.endereco') }}
                        @if (config('nova.telefone')) · {{ config('nova.telefone') }} @endif
                    </div>
                </td>
                <td class="titulo-os">
                    <div class="numero">Ordem de serviço #{{ $pedido->id }}</div>
                    <div class="data">Emitida em {{ now()->format('d/m/Y \à\s H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="dados">
        <tr>
            <td class="rotulo">Ótica</td>
            <td>{{ $pedido->otica?->nome_fantasia }}{{ $pedido->otica?->cnpj ? ' - '.$pedido->otica->cnpj : '' }}</td>
        </tr>
        <tr>
            <td class="rotulo">Cliente final</td>
            <td>{{ $pedido->cliente_nome }}{{ $pedido->cliente_telefone ? ' - '.$pedido->cliente_telefone : '' }}</td>
        </tr>
        <tr>
            <td class="rotulo">Pedido recebido em</td>
            <td>{{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</td>
        </tr>
        <tr>
            <td class="rotulo">Status atual</td>
            <td><span class="status">{{ $pedido->statusLabel() }}</span></td>
        </tr>
    </table>

    <div class="secao">
        <div class="secao-titulo">Receita</div>
        <table class="receita">
            <tr>
                <th></th>
                <th>Esférico</th>
                <th>Cilíndrico</th>
                <th>Eixo</th>
                <th>Adição</th>
            </tr>
            <tr>
                <td><strong>OD</strong></td>
                <td>{{ $pedido->od_esferico ?? '—' }}</td>
                <td>{{ $pedido->od_cilindrico ?? '—' }}</td>
                <td>{{ $pedido->od_eixo ?? '—' }}</td>
                <td>{{ $pedido->od_adicao ?? '—' }}</td>
            </tr>
            <tr>
                <td><strong>OE</strong></td>
                <td>{{ $pedido->oe_esferico ?? '—' }}</td>
                <td>{{ $pedido->oe_cilindrico ?? '—' }}</td>
                <td>{{ $pedido->oe_eixo ?? '—' }}</td>
                <td>{{ $pedido->oe_adicao ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="secao">
        <div class="secao-titulo">Lente, tratamentos e montagem</div>
        <table class="dados">
            <tr>
                <td class="rotulo">Lente</td>
                <td>{{ $pedido->lente_nome ?? '—' }}</td>
            </tr>
            <tr>
                <td class="rotulo">Tratamentos</td>
                <td>{{ $pedido->tratamentos->pluck('tratamento_nome')->join(', ') ?: 'Nenhum' }}</td>
            </tr>
            <tr>
                <td class="rotulo">Montagem</td>
                <td>{{ $pedido->com_montagem ? 'Sim' : 'Não' }}</td>
            </tr>
            @if ($pedido->com_montagem && $pedido->montagem_observacoes)
                <tr>
                    <td class="rotulo">Observações da montagem</td>
                    <td>{{ $pedido->montagem_observacoes }}</td>
                </tr>
            @endif
        </table>
    </div>

    @if ($pedido->temDadosArmacao())
        <div class="secao">
            <div class="secao-titulo">Armação</div>
            <table class="dados">
                @if ($pedido->montagem_formato_armacao && $pedido->montagem_formato_armacao !== \App\Models\Pedido::FORMATO_UPLOAD)
                    <tr>
                        <td class="rotulo">Formato</td>
                        <td>{{ $pedido->formatoArmacaoLabel() }}</td>
                    </tr>
                @endif
                @if ($pedido->montagem_mva || $pedido->montagem_mha || $pedido->montagem_dma || $pedido->montagem_ponte || $pedido->montagem_dpa)
                    <tr>
                        <td class="rotulo">Medidas</td>
                        <td>
                            @if ($pedido->montagem_mva) MVA {{ $pedido->montagem_mva }} mm @endif
                            @if ($pedido->montagem_mha) &nbsp;·&nbsp; MHA {{ $pedido->montagem_mha }} mm @endif
                            @if ($pedido->montagem_dma) &nbsp;·&nbsp; DMA {{ $pedido->montagem_dma }} mm @endif
                            @if ($pedido->montagem_ponte) &nbsp;·&nbsp; Ponte {{ $pedido->montagem_ponte }} mm @endif
                            @if ($pedido->montagem_dpa) &nbsp;·&nbsp; DPA {{ $pedido->montagem_dpa }} mm @endif
                        </td>
                    </tr>
                @endif
                @if ($pedido->montagem_diametro_od || $pedido->montagem_diametro_oe)
                    <tr>
                        <td class="rotulo">Diâmetro estimado</td>
                        <td>O.D. {{ $pedido->montagem_diametro_od ?? '—' }} mm · O.E. {{ $pedido->montagem_diametro_oe ?? '—' }} mm</td>
                    </tr>
                @endif
                @if ($pedido->montagem_clipon)
                    <tr>
                        <td class="rotulo">Clip-on</td>
                        <td>{{ $pedido->cliponLabel() }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="rotulo">Armação enviada</td>
                    <td>{{ $pedido->montagem_enviar_armacao ? 'Sim' : 'Não' }}</td>
                </tr>
            </table>

            @if ($pedido->montagem_foto_armacao && \Illuminate\Support\Facades\Storage::disk('public')->exists($pedido->montagem_foto_armacao))
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->path($pedido->montagem_foto_armacao) }}" alt="Foto da armação" style="margin-top: 8px; max-height: 140px; border: 1px solid #d8dee8; border-radius: 6px;">
            @endif
        </div>
    @endif

    @if ($pedido->temDadosProfissional())
        <div class="secao">
            <div class="secao-titulo">Paciente e profissional</div>
            <table class="dados">
                <tr>
                    <td class="rotulo">Receita passada por</td>
                    <td>
                        {{ $pedido->tipoProfissionalLabel() }}
                        @if ($pedido->profissional_nome) — {{ $pedido->profissional_nome }} @endif
                        @if ($pedido->tipo_profissional === \App\Models\Pedido::TIPO_PROFISSIONAL_MEDICO && ($pedido->profissional_uf_crm || $pedido->profissional_crm))
                            (CRM {{ $pedido->profissional_uf_crm }} {{ $pedido->profissional_crm }})
                        @endif
                    </td>
                </tr>
                @if ($pedido->paciente_iniciais || $pedido->paciente_idade || $pedido->paciente_complemento)
                    <tr>
                        <td class="rotulo">Paciente</td>
                        <td>{{ collect([$pedido->paciente_iniciais, $pedido->paciente_idade ? $pedido->paciente_idade.' anos' : null, $pedido->paciente_complemento])->filter()->join(' · ') }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    <div class="secao">
        <div class="secao-titulo">Valores</div>
        <table class="valores">
            <tr>
                <td>Lente (OD)</td>
                <td class="valor">R$ {{ number_format((float) $pedido->preco_lente_od, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Lente (OE)</td>
                <td class="valor">R$ {{ number_format((float) $pedido->preco_lente_oe, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tratamentos</td>
                <td class="valor">R$ {{ number_format((float) $pedido->preco_tratamentos, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Montagem</td>
                <td class="valor">R$ {{ number_format((float) $pedido->preco_montagem, 2, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td class="valor">R$ {{ number_format((float) $pedido->preco_total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="rodape">
        {{ config('nova.nome') }} {{ config('nova.subtitulo') }} — documento gerado automaticamente, sem valor fiscal.
    </div>

</body>
</html>
