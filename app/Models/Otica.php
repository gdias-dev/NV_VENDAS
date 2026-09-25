<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A ótica é autenticável (guard "otica"): ela mesma loga no portal de pedidos,
 * separado do painel administrativo (guard "web", usuários da Nova Varonil).
 */
class Otica extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'oticas';

    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_APROVADA = 'aprovada';
    public const STATUS_BLOQUEADA = 'bloqueada';

    public const STATUSES = [
        self::STATUS_PENDENTE => 'Pendente',
        self::STATUS_APROVADA => 'Aprovada',
        self::STATUS_BLOQUEADA => 'Bloqueada',
    ];

    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'password',
        'telefone',
        'endereco',
        'tabela_preco_id',
        'status',
        'observacoes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tabelaPreco(): BelongsTo
    {
        return $this->belongsTo(TabelaPreco::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function isAprovada(): bool
    {
        return $this->status === self::STATUS_APROVADA;
    }

    public function isPendente(): bool
    {
        return $this->status === self::STATUS_PENDENTE;
    }

    public function isBloqueada(): bool
    {
        return $this->status === self::STATUS_BLOQUEADA;
    }
}
