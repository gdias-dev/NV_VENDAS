<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecoTratamento extends Model
{
    use HasFactory;

    protected $table = 'precos_tratamento';

    protected $fillable = [
        'tratamento_id',
        'tabela_preco_id',
        'preco',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
        ];
    }

    public function tratamento(): BelongsTo
    {
        return $this->belongsTo(Tratamento::class);
    }

    public function tabelaPreco(): BelongsTo
    {
        return $this->belongsTo(TabelaPreco::class);
    }
}
