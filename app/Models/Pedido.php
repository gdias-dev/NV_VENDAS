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
        'preco_lente_od',
        'preco_lente_oe',
        'preco_tratamentos',
        'preco_montagem',
        'preco_total',
        'status',
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
            'preco_lente_od' => 'decimal:2',
            'preco_lente_oe' => 'decimal:2',
            'preco_tratamentos' => 'decimal:2',
            'preco_montagem' => 'decimal:2',
            'preco_total' => 'decimal:2',
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
}
