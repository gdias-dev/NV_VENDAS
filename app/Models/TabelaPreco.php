<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TabelaPreco extends Model
{
    use HasFactory;

    protected $table = 'tabelas_preco';

    protected $fillable = [
        'nome',
        'descricao',
        'valor_montagem',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'valor_montagem' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function oticas(): HasMany
    {
        return $this->hasMany(Otica::class);
    }

    public function faixasPreco(): HasMany
    {
        return $this->hasMany(FaixaPrecoLente::class);
    }

    public function precosTratamento(): HasMany
    {
        return $this->hasMany(PrecoTratamento::class);
    }
}
