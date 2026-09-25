<?php

namespace Database\Seeders;

use App\Models\FaixaPrecoLente;
use App\Models\Lente;
use App\Models\PrecoTratamento;
use App\Models\TabelaPreco;
use App\Models\Tratamento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Usuário administrador inicial ---------------------------------
        // IMPORTANTE: troque essa senha assim que fizer o primeiro login.
        $adminEmail = env('ADMIN_SEED_EMAIL', 'admin@novavaronillab.com.br');
        $adminSenha = env('ADMIN_SEED_SENHA', 'trocar123');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrador Nova Varonil',
                'password' => Hash::make($adminSenha),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // ---- Tabela de preço padrão -----------------------------------------
        $padrao = TabelaPreco::updateOrCreate(
            ['nome' => 'Padrão'],
            [
                'descricao' => 'Tabela padrão para novas óticas, até que uma condição especial seja negociada.',
                'valor_montagem' => 25.00,
                'is_default' => true,
            ]
        );

        // ---- Tratamentos de exemplo (substitua/ajuste os preços reais) ------
        $tratamentos = [
            'Antirreflexo' => 45.00,
            'Fotossensível' => 120.00,
            'Filtro de luz azul' => 60.00,
            'Endurecimento' => 20.00,
        ];

        foreach ($tratamentos as $nome => $preco) {
            $tratamento = Tratamento::updateOrCreate(['nome' => $nome], ['ativo' => true]);

            PrecoTratamento::updateOrCreate(
                ['tratamento_id' => $tratamento->id, 'tabela_preco_id' => $padrao->id],
                ['preco' => $preco]
            );
        }

        // ---- Lentes de exemplo com faixas de preço por grau ------------------
        $visaoSimples = Lente::updateOrCreate(
            ['nome' => 'Visão Simples CR-39 1.56'],
            ['tipo' => Lente::TIPO_VISAO_SIMPLES, 'material' => 'Resina', 'indice' => '1.56', 'ativo' => true]
        );

        $faixasVisaoSimples = [
            ['esferico_min' => -6.00, 'esferico_max' => 0.00, 'cilindrico_min' => -2.00, 'cilindrico_max' => 0.00, 'preco' => 60.00],
            ['esferico_min' => 0.25, 'esferico_max' => 6.00, 'cilindrico_min' => -2.00, 'cilindrico_max' => 0.00, 'preco' => 60.00],
            ['esferico_min' => -10.00, 'esferico_max' => -6.25, 'cilindrico_min' => -2.00, 'cilindrico_max' => 0.00, 'preco' => 90.00],
        ];

        foreach ($faixasVisaoSimples as $faixa) {
            FaixaPrecoLente::updateOrCreate(
                [
                    'lente_id' => $visaoSimples->id,
                    'tabela_preco_id' => $padrao->id,
                    'esferico_min' => $faixa['esferico_min'],
                    'esferico_max' => $faixa['esferico_max'],
                ],
                $faixa
            );
        }

        $multifocal = Lente::updateOrCreate(
            ['nome' => 'Multifocal Padrão 1.56'],
            ['tipo' => Lente::TIPO_MULTIFOCAL, 'material' => 'Resina', 'indice' => '1.56', 'ativo' => true]
        );

        FaixaPrecoLente::updateOrCreate(
            [
                'lente_id' => $multifocal->id,
                'tabela_preco_id' => $padrao->id,
                'esferico_min' => -6.00,
                'esferico_max' => 6.00,
            ],
            [
                'cilindrico_min' => -2.00,
                'cilindrico_max' => 0.00,
                'preco' => 280.00,
            ]
        );
    }
}
