<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    public const STATUS_RECEBIDO = 'recebido';
    public const STATUS_PRODUCAO = 'producao';
    public const STATUS_EXPEDICAO = 'expedicao';
    public const STATUS_ENTREGUE = 'entregue';
    public const STATUS_CANCELADO = 'cancelado';

    public const STATUSES = [
        self::STATUS_RECEBIDO => 'Recebido',
        self::STATUS_PRODUCAO => 'Em produção',
        self::STATUS_EXPEDICAO => 'Em expedição',
        self::STATUS_ENTREGUE => 'Entregue',
        self::STATUS_CANCELADO => 'Cancelado',
    ];

    public const FORMA_DINHEIRO = 'dinheiro';
    public const FORMA_PIX = 'pix';
    public const FORMA_CARTAO = 'cartao';
    public const FORMA_BOLETO = 'boleto';
    public const FORMA_OUTRO = 'outro';

    public const FORMAS_PAGAMENTO = [
        self::FORMA_DINHEIRO => 'Dinheiro',
        self::FORMA_PIX => 'Pix',
        self::FORMA_CARTAO => 'Cartão',
        self::FORMA_BOLETO => 'Boleto',
        self::FORMA_OUTRO => 'Outro',
    ];

    public const FORMATO_UPLOAD = 'upload';

    // Formatos de armação disponíveis para escolha na montagem. "upload" é
    // tratado à parte: em vez de escolher um formato, a ótica manda uma foto.
    public const FORMATOS_ARMACAO = [
        'quadrada' => 'Quadrada arredondada',
        'aviador' => 'Aviador',
        'trapezio' => 'Trapézio',
        'retangular_arredondada' => 'Retangular arredondada',
        'oval' => 'Oval',
        'redonda' => 'Redonda',
        'retangular_classica' => 'Retangular clássica',
        'redonda_fio' => 'Redonda (fio fino)',
        'hexagonal' => 'Hexagonal',
        'retangular_metal' => 'Retangular (metal)',
        self::FORMATO_UPLOAD => 'Enviar foto da armação',
    ];

    public const CLIPON_NAO = 'nao';
    public const CLIPON_SIM = 'sim';
    public const CLIPON_NAO_INFORMADO = 'nao_informado';

    public const CLIPON_OPTIONS = [
        self::CLIPON_NAO => 'Não',
        self::CLIPON_SIM => 'Sim',
        self::CLIPON_NAO_INFORMADO => 'Não informado',
    ];

    protected $fillable = [
        'otica_id',
        'cliente_nome',
        'cliente_telefone',
        'lente_id',
        'lente_nome',
        'tabela_preco_id',
        'od_esferico',
        'od_cilindrico',
        'od_eixo',
        'od_adicao',
        'oe_esferico',
        'oe_cilindrico',
        'oe_eixo',
        'oe_adicao',
        'com_montagem',
        'montagem_observacoes',
        'montagem_formato_armacao',
        'montagem_foto_armacao',
        'montagem_mva',
        'montagem_mha',
        'montagem_dma',
        'montagem_ponte',
        'montagem_dpa',
        'montagem_diametro_od',
        'montagem_diametro_oe',
        'montagem_clipon',
        'montagem_enviar_armacao',
        'preco_lente_od',
        'preco_lente_oe',
        'preco_tratamentos',
        'preco_montagem',
        'preco_total',
        'status',
        'pago',
        'pago_em',
        'forma_pagamento',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'od_esferico' => 'decimal:2',
            'od_cilindrico' => 'decimal:2',
            'od_eixo' => 'integer',
            'od_adicao' => 'decimal:2',
            'oe_esferico' => 'decimal:2',
            'oe_cilindrico' => 'decimal:2',
            'oe_eixo' => 'integer',
            'oe_adicao' => 'decimal:2',
            'com_montagem' => 'boolean',
            'montagem_mva' => 'decimal:2',
            'montagem_mha' => 'decimal:2',
            'montagem_dma' => 'decimal:2',
            'montagem_ponte' => 'decimal:2',
            'montagem_dpa' => 'decimal:2',
            'montagem_diametro_od' => 'decimal:2',
            'montagem_diametro_oe' => 'decimal:2',
            'montagem_enviar_armacao' => 'boolean',
            'preco_lente_od' => 'decimal:2',
            'preco_lente_oe' => 'decimal:2',
            'preco_tratamentos' => 'decimal:2',
            'preco_montagem' => 'decimal:2',
            'preco_total' => 'decimal:2',
            'pago' => 'boolean',
            'pago_em' => 'date',
        ];
    }

    public function otica(): BelongsTo
    {
        return $this->belongsTo(Otica::class);
    }

    public function lente(): BelongsTo
    {
        return $this->belongsTo(Lente::class);
    }

    public function tabelaPreco(): BelongsTo
    {
        return $this->belongsTo(TabelaPreco::class);
    }

    public function tratamentos(): HasMany
    {
        return $this->hasMany(PedidoTratamento::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isCancelado(): bool
    {
        return $this->status === self::STATUS_CANCELADO;
    }

    public function formaPagamentoLabel(): ?string
    {
        return $this->forma_pagamento ? (self::FORMAS_PAGAMENTO[$this->forma_pagamento] ?? $this->forma_pagamento) : null;
    }

    public function formatoArmacaoLabel(): ?string
    {
        return $this->montagem_formato_armacao
            ? (self::FORMATOS_ARMACAO[$this->montagem_formato_armacao] ?? $this->montagem_formato_armacao)
            : null;
    }

    public function cliponLabel(): ?string
    {
        return $this->montagem_clipon
            ? (self::CLIPON_OPTIONS[$this->montagem_clipon] ?? $this->montagem_clipon)
            : null;
    }

    /**
     * Se tem alguma informação de armação preenchida, além do "com montagem"
     * simples — pra decidir se mostra a seção de armação/medidas ou não.
     */
    public function temDadosArmacao(): bool
    {
        return $this->com_montagem && (
            $this->montagem_formato_armacao
            || $this->montagem_foto_armacao
            || $this->montagem_mva
            || $this->montagem_mha
            || $this->montagem_dma
            || $this->montagem_ponte
            || $this->montagem_dpa
        );
    }
}
