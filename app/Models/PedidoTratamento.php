<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoTratamento extends Model
{
    use HasFactory;

    protected $table = 'pedido_tratamento';

    protected $fillable = [
        'pedido_id',
        'tratamento_id',
        'tratamento_nome',
        'preco',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function tratamento(): BelongsTo
    {
        return $this->belongsTo(Tratamento::class);
    }
}
