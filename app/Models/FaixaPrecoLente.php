<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaixaPrecoLente extends Model
{
    use HasFactory;

    protected $table = 'faixas_preco_lente';

    protected $fillable = [
        'lente_id',
        'tabela_preco_id',
        'esferico_min',
        'esferico_max',
        'cilindrico_min',
        'cilindrico_max',
        'preco',
    ];

    protected function casts(): array
    {
        return [
            'esferico_min' => 'decimal:2',
            'esferico_max' => 'decimal:2',
            'cilindrico_min' => 'decimal:2',
            'cilindrico_max' => 'decimal:2',
            'preco' => 'decimal:2',
        ];
    }

    public function lente(): BelongsTo
    {
        return $this->belongsTo(Lente::class);
    }

    public function tabelaPreco(): BelongsTo
    {
        return $this->belongsTo(TabelaPreco::class);
    }
}
