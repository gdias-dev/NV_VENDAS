<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lente extends Model
{
    use HasFactory;

    protected $table = 'lentes';

    public const TIPO_VISAO_SIMPLES = 'visao_simples';
    public const TIPO_MULTIFOCAL = 'multifocal';
    public const TIPO_BIFOCAL = 'bifocal';

    public const TIPOS = [
        self::TIPO_VISAO_SIMPLES => 'Visão simples',
        self::TIPO_MULTIFOCAL => 'Multifocal',
        self::TIPO_BIFOCAL => 'Bifocal',
    ];

    protected $fillable = [
        'nome',
        'tipo',
        'material',
        'indice',
        'descricao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function faixasPreco(): HasMany
    {
        return $this->hasMany(FaixaPrecoLente::class);
    }
}
