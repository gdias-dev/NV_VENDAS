<?php

namespace App\Livewire\Portal;

use App\Mail\NovoPedido;
use App\Models\FaixaPrecoLente;
use App\Models\Lente;
use App\Models\Otica;
use App\Models\Pedido;
use App\Models\PrecoTratamento;
use App\Models\Tratamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

/**
 * Assistente de pedido, em 7 passos: identificação do cliente, receita,
 * lente, tratamentos, montagem, paciente/profissional e resumo com o preço
 * calculado automaticamente a partir da tabela de preço da ótica logada.
 */
class AssistentePedido extends Component
{
    use WithFileUploads;

    protected const TOTAL_PASSOS = 7;

    public int $step = 1;

    // Passo 1 — identificação do cliente final.
    public string $clienteNome = '';
    public ?string $clienteTelefone = null;

    // Passo 2 — receita (OD = olho direito, OE = olho esquerdo).
    public ?string $odEsferico = null;
    public ?string $odCilindrico = null;
    public ?string $odEixo = null;
    public ?string $odAdicao = null;
    public ?string $oeEsferico = null;
    public ?string $oeCilindrico = null;
    public ?string $oeEixo = null;
    public ?string $oeAdicao = null;

    // Passo 3 — lente escolhida.
    public ?int $lenteId = null;

    // Passo 4 — tratamentos escolhidos (ids).
    public array $tratamentosSelecionados = [];

    // Passo 5 — montagem.
    public bool $comMontagem = false;
    public ?string $montagemObservacoes = null;
    public ?string $montagemFormatoArmacao = null;
    public $montagemFotoArmacao = null;
    public ?string $montagemMva = null;
    public ?string $montagemMha = null;
    public ?string $montagemDma = null;
    public ?string $montagemPonte = null;
    public ?string $montagemDpa = null;
    public string $montagemClipon = Pedido::CLIPON_NAO_INFORMADO;
    public bool $montagemEnviarArmacao = false;

    // Passo 6 — paciente e profissional que passou a receita.
    public string $tipoProfissional = Pedido::TIPO_PROFISSIONAL_MEDICO;
    public ?string $profissionalNome = null;
    public ?string $profissionalUfCrm = null;
    public ?string $profissionalCrm = null;
    public ?string $pacienteIniciais = null;
    public ?string $pacienteIdade = null;
    public ?string $pacienteComplemento = null;

    // Resultado, após confirmar.
    public bool $pedidoCriado = false;
    public ?int $pedidoId = null;

    public function mount(): void
    {
        if (! $this->otica()?->isAprovada()) {
            abort(403);
        }
    }

    public function totalPassos(): int
    {
        return self::TOTAL_PASSOS;
    }

    protected function otica(): ?Otica
    {
        /** @var Otica|null $otica */
        $otica = Auth::guard('otica')->user();

        return $otica;
    }

    #[Computed]
    public function lentesDisponiveis()
    {
        return Lente::where('ativo', true)->orderBy('nome')->get();
    }

    #[Computed]
    public function tratamentosDisponiveis()
    {
        return Tratamento::where('ativo', true)->orderBy('nome')->get();
    }

    #[Computed]
    public function lenteSelecionada(): ?Lente
    {
        if (! $this->lenteId) {
            return null;
        }

        return Lente::find($this->lenteId);
    }

    protected function rulesParaPasso(int $step): array
    {
        return match ($step) {
            1 => [
                'clienteNome' => ['required', 'string', 'max:255'],
                'clienteTelefone' => ['nullable', 'string', 'max:30'],
            ],
            2 => [
                'odEsferico' => ['nullable', 'numeric', 'between:-30,30'],
                'odCilindrico' => ['nullable', 'numeric', 'between:-10,10'],
                'odEixo' => ['nullable', 'integer', 'between:0,180'],
                'odAdicao' => ['nullable', 'numeric', 'between:0,5'],
                'oeEsferico' => ['nullable', 'numeric', 'between:-30,30'],
                'oeCilindrico' => ['nullable', 'numeric', 'between:-10,10'],
                'oeEixo' => ['nullable', 'integer', 'between:0,180'],
                'oeAdicao' => ['nullable', 'numeric', 'between:0,5'],
            ],
            3 => [
                'lenteId' => ['required', 'integer', 'exists:lentes,id'],
            ],
            4 => [
                'tratamentosSelecionados' => ['array'],
                'tratamentosSelecionados.*' => ['integer', 'exists:tratamentos,id'],
            ],
            5 => [
                'montagemObservacoes' => ['nullable', 'string', 'max:500'],
                'montagemFormatoArmacao' => ['nullable', 'string', Rule::in(array_keys(Pedido::FORMATOS_ARMACAO))],
                'montagemFotoArmacao' => ['nullable', 'image', 'max:5120'],
                'montagemMva' => ['nullable', 'numeric', 'between:0,80'],
                'montagemMha' => ['nullable', 'numeric', 'between:0,80'],
                'montagemDma' => ['nullable', 'numeric', 'between:0,80'],
                'montagemPonte' => ['nullable', 'numeric', 'between:0,40'],
                'montagemDpa' => ['nullable', 'numeric', 'between:0,40'],
                'montagemClipon' => ['nullable', 'string', Rule::in(array_keys(Pedido::CLIPON_OPTIONS))],
                'montagemEnviarArmacao' => ['boolean'],
            ],
            6 => [
                'tipoProfissional' => ['required', 'string', Rule::in(array_keys(Pedido::TIPOS_PROFISSIONAL))],
                'profissionalNome' => ['nullable', 'string', 'max:255'],
                'profissionalUfCrm' => ['nullable', 'string', Rule::in(Pedido::UFS_BRASIL)],
                'profissionalCrm' => ['nullable', 'string', 'max:20'],
                'pacienteIniciais' => ['nullable', 'string', 'max:10'],
                'pacienteIdade' => ['nullable', 'integer', 'between:0,120'],
                'pacienteComplemento' => ['nullable', 'string', 'max:255'],
            ],
            default => [],
        };
    }

    protected function labelsCampos(): array
    {
        return [
            'clienteNome' => 'nome do cliente',
            'lenteId' => 'lente',
        ];
    }

    public function proximo(): void
    {
        $this->validate($this->rulesParaPasso($this->step), [], $this->labelsCampos());

        if ($this->step < self::TOTAL_PASSOS) {
            $this->step++;
        }
    }

    public function voltar(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function irParaPasso(int $step): void
    {
        // Só permite voltar para um passo já preenchido, nunca pular para a frente.
        if ($step >= 1 && $step < $this->step) {
            $this->step = $step;
        }
    }

    /**
     * Busca o preço de uma lente para um olho, pela faixa de grau (esférico e
     * cilíndrico) cadastrada na tabela de preço da ótica. Faixas com min/max
     * em branco valem como "sem limite" naquele lado.
     */
    protected function buscarPrecoLente(int $tabelaPrecoId, float $esferico, float $cilindrico): float
    {
        if (! $this->lenteId) {
            return 0.0;
        }

        $faixa = FaixaPrecoLente::where('lente_id', $this->lenteId)
            ->where('tabela_preco_id', $tabelaPrecoId)
            ->where(function ($query) use ($esferico) {
                $query->whereNull('esferico_min')->orWhere('esferico_min', '<=', $esferico);
            })
            ->where(function ($query) use ($esferico) {
                $query->whereNull('esferico_max')->orWhere('esferico_max', '>=', $esferico);
            })
            ->where(function ($query) use ($cilindrico) {
                $query->whereNull('cilindrico_min')->orWhere('cilindrico_min', '<=', $cilindrico);
            })
            ->where(function ($query) use ($cilindrico) {
                $query->whereNull('cilindrico_max')->orWhere('cilindrico_max', '>=', $cilindrico);
            })
            ->orderByRaw('(cilindrico_min IS NULL OR cilindrico_max IS NULL) asc')
            ->first();

        return $faixa ? (float) $faixa->preco : 0.0;
    }

    #[Computed]
    public function resumo(): array
    {
        $otica = $this->otica();
        $tabelaPrecoId = $otica?->tabela_preco_id;

        $odEsferico = (float) ($this->odEsferico ?: 0);
        $odCilindrico = (float) ($this->odCilindrico ?: 0);
        $oeEsferico = (float) ($this->oeEsferico ?: 0);
        $oeCilindrico = (float) ($this->oeCilindrico ?: 0);

        $precoLenteOd = $tabelaPrecoId ? $this->buscarPrecoLente($tabelaPrecoId, $odEsferico, $odCilindrico) : 0.0;
        $precoLenteOe = $tabelaPrecoId ? $this->buscarPrecoLente($tabelaPrecoId, $oeEsferico, $oeCilindrico) : 0.0;

        $precoTratamentos = 0.0;
        $tratamentosResumo = [];

        if ($tabelaPrecoId && ! empty($this->tratamentosSelecionados)) {
            $precos = PrecoTratamento::whereIn('tratamento_id', $this->tratamentosSelecionados)
                ->where('tabela_preco_id', $tabelaPrecoId)
                ->with('tratamento')
                ->get();

            foreach ($precos as $precoTratamento) {
                $precoTratamentos += (float) $precoTratamento->preco;
                $tratamentosResumo[] = [
                    'id' => $precoTratamento->tratamento_id,
                    'nome' => $precoTratamento->tratamento?->nome ?? 'Tratamento',
                    'preco' => (float) $precoTratamento->preco,
                ];
            }
        }

        $precoMontagem = $this->comMontagem
            ? (float) ($otica?->tabelaPreco?->valor_montagem ?? 0)
            : 0.0;

        return [
            'preco_lente_od' => $precoLenteOd,
            'preco_lente_oe' => $precoLenteOe,
            'preco_tratamentos' => $precoTratamentos,
            'preco_montagem' => $precoMontagem,
            'preco_total' => $precoLenteOd + $precoLenteOe + $precoTratamentos + $precoMontagem,
            'tratamentos' => $tratamentosResumo,
        ];
    }

    protected function valorOuNulo(?string $valor): ?string
    {
        return ($valor === null || $valor === '') ? null : $valor;
    }

    /**
     * Estimativa simples do diâmetro mínimo da lente, a partir da DMA (diagonal
     * maior da armação) informada, ou da MHA (largura) se a DMA não vier
     * preenchida. É só uma ajuda para a montagem — o laboratório sempre confere
     * antes de cortar a lente.
     */
    #[Computed]
    public function diametroEstimado(): array
    {
        $dma = $this->montagemDma !== null && $this->montagemDma !== '' ? (float) $this->montagemDma : null;
        $mha = $this->montagemMha !== null && $this->montagemMha !== '' ? (float) $this->montagemMha : null;

        $estimado = $dma ?? $mha;

        return [
            'od' => $estimado,
            'oe' => $estimado,
        ];
    }

    public function confirmar(): void
    {
        $regras = array_merge(
            $this->rulesParaPasso(1),
            $this->rulesParaPasso(2),
            $this->rulesParaPasso(3),
            $this->rulesParaPasso(4),
            $this->rulesParaPasso(5),
            $this->rulesParaPasso(6),
        );

        $this->validate($regras, [], $this->labelsCampos());

        $otica = $this->otica();

        if (! $otica || ! $otica->isAprovada()) {
            abort(403);
        }

        $resumo = $this->resumo;
        $lente = $this->lenteSelecionada;
        $diametro = $this->diametroEstimado;

        // Sobe a foto da armação (se veio uma) fora da transação, já que é um
        // upload de arquivo, não uma operação de banco.
        $fotoArmacao = null;

        if ($this->comMontagem && $this->montagemFormatoArmacao === Pedido::FORMATO_UPLOAD && $this->montagemFotoArmacao) {
            $fotoArmacao = $this->montagemFotoArmacao->store('armacoes', 'public');
        }

        $pedido = DB::transaction(function () use ($otica, $resumo, $lente, $diametro, $fotoArmacao) {
            $pedido = Pedido::create([
                'otica_id' => $otica->id,
                'cliente_nome' => $this->clienteNome,
                'cliente_telefone' => $this->clienteTelefone,
                'lente_id' => $this->lenteId,
                'lente_nome' => $lente?->nome,
                'tabela_preco_id' => $otica->tabela_preco_id,
                'od_esferico' => $this->valorOuNulo($this->odEsferico),
                'od_cilindrico' => $this->valorOuNulo($this->odCilindrico),
                'od_eixo' => $this->valorOuNulo($this->odEixo),
                'od_adicao' => $this->valorOuNulo($this->odAdicao),
                'oe_esferico' => $this->valorOuNulo($this->oeEsferico),
                'oe_cilindrico' => $this->valorOuNulo($this->oeCilindrico),
                'oe_eixo' => $this->valorOuNulo($this->oeEixo),
                'oe_adicao' => $this->valorOuNulo($this->oeAdicao),
                'tipo_profissional' => $this->tipoProfissional,
                'profissional_nome' => $this->valorOuNulo($this->profissionalNome),
                'profissional_uf_crm' => $this->tipoProfissional === Pedido::TIPO_PROFISSIONAL_MEDICO ? $this->valorOuNulo($this->profissionalUfCrm) : null,
                'profissional_crm' => $this->tipoProfissional === Pedido::TIPO_PROFISSIONAL_MEDICO ? $this->valorOuNulo($this->profissionalCrm) : null,
                'paciente_iniciais' => $this->valorOuNulo($this->pacienteIniciais),
                'paciente_idade' => $this->valorOuNulo($this->pacienteIdade),
                'paciente_complemento' => $this->valorOuNulo($this->pacienteComplemento),
                'com_montagem' => $this->comMontagem,
                'montagem_observacoes' => $this->comMontagem ? $this->montagemObservacoes : null,
                'montagem_formato_armacao' => $this->comMontagem ? $this->montagemFormatoArmacao : null,
                'montagem_foto_armacao' => $this->comMontagem ? $fotoArmacao : null,
                'montagem_mva' => $this->comMontagem ? $this->valorOuNulo($this->montagemMva) : null,
                'montagem_mha' => $this->comMontagem ? $this->valorOuNulo($this->montagemMha) : null,
                'montagem_dma' => $this->comMontagem ? $this->valorOuNulo($this->montagemDma) : null,
                'montagem_ponte' => $this->comMontagem ? $this->valorOuNulo($this->montagemPonte) : null,
                'montagem_dpa' => $this->comMontagem ? $this->valorOuNulo($this->montagemDpa) : null,
                'montagem_diametro_od' => $this->comMontagem ? $diametro['od'] : null,
                'montagem_diametro_oe' => $this->comMontagem ? $diametro['oe'] : null,
                'montagem_clipon' => $this->comMontagem ? $this->montagemClipon : null,
                'montagem_enviar_armacao' => $this->comMontagem ? $this->montagemEnviarArmacao : false,
                'preco_lente_od' => $resumo['preco_lente_od'],
                'preco_lente_oe' => $resumo['preco_lente_oe'],
                'preco_tratamentos' => $resumo['preco_tratamentos'],
                'preco_montagem' => $resumo['preco_montagem'],
                'preco_total' => $resumo['preco_total'],
                'status' => Pedido::STATUS_RECEBIDO,
            ]);

            foreach ($resumo['tratamentos'] as $tratamento) {
                $pedido->tratamentos()->create([
                    'tratamento_id' => $tratamento['id'],
                    'tratamento_nome' => $tratamento['nome'],
                    'preco' => $tratamento['preco'],
                ]);
            }

            return $pedido;
        });

        $destino = config('nova.email_contato');

        if ($destino) {
            try {
                Mail::to($destino)->send(new NovoPedido($pedido));
            } catch (Throwable $e) {
                Log::error('Falha ao avisar o laboratório sobre novo pedido: '.$e->getMessage(), [
                    'pedido_id' => $pedido->id,
                ]);
            }
        }

        $this->pedidoCriado = true;
        $this->pedidoId = $pedido->id;
    }

    public function render()
    {
        return view('livewire.portal.assistente-pedido');
    }
}
