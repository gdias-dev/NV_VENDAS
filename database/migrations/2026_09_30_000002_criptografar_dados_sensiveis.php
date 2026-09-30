<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Criptografa, em repouso (no banco de dados), os dados pessoais que o
 * sistema guarda: telefone/endereço/CNPJ da ótica, e nome/telefone do
 * cliente final + dados do paciente/profissional no pedido.
 *
 * O que NÃO foi criptografado (e por quê):
 * - E-mail (ótica e usuário admin): precisa ser buscável/único para o login.
 * - Nome fantasia/razão social da ótica: exibido e buscado o tempo todo.
 * - Receita (grau dos olhos): são números (decimal), e o cast "encrypted"
 *   do Laravel não combina com cast numérico — ficaria pra uma etapa à
 *   parte, com um cast customizado, se for necessário no futuro.
 *
 * Colunas de texto normal (varchar) são pequenas demais para guardar um
 * valor criptografado (o texto cifrado fica bem maior que o original), por
 * isso a migration amplia essas colunas para TEXT antes de reescrever os
 * dados já existentes.
 */
return new class extends Migration
{
    private const CAMPOS_OTICA = ['telefone', 'endereco', 'cnpj'];

    private const CAMPOS_PEDIDO = [
        'cliente_nome',
        'cliente_telefone',
        'paciente_iniciais',
        'paciente_complemento',
        'profissional_nome',
        'profissional_crm',
    ];

    public function up(): void
    {
        // O índice único em "cnpj" não pode conviver com uma coluna TEXT
        // (e, de qualquer forma, deixaria de funcionar: o texto criptografado
        // é diferente a cada vez, mesmo para o mesmo CNPJ). No lugar dele,
        // criamos uma coluna auxiliar "cnpj_hash" com o hash do CNPJ em
        // texto puro, e é essa coluna que passa a garantir "não duplicado".
        Schema::table('oticas', function (Blueprint $table): void {
            $table->dropUnique('oticas_cnpj_unique');
        });

        Schema::table('oticas', function (Blueprint $table): void {
            $table->string('cnpj_hash', 64)->nullable()->after('cnpj');
        });

        foreach (self::CAMPOS_OTICA as $campo) {
            DB::statement("ALTER TABLE oticas MODIFY {$campo} TEXT NULL");
        }

        foreach (self::CAMPOS_PEDIDO as $campo) {
            // "cliente_nome" é obrigatório desde a criação da tabela — mantém
            // esse NOT NULL mesmo depois de virar TEXT.
            $nulo = $campo === 'cliente_nome' ? 'NOT NULL' : 'NULL';
            DB::statement("ALTER TABLE pedidos MODIFY {$campo} TEXT {$nulo}");
        }

        // Criptografa os dados que já existem no banco (cadastros de teste
        // feitos antes desta etapa). Usa o mesmo mecanismo do cast
        // "encrypted" do Eloquent (Crypt::encryptString), lendo e
        // gravando direto pelo Query Builder para não disparar os casts
        // do model (que já vamos configurar para criptografar sozinhos
        // dali em diante).
        DB::table('oticas')->orderBy('id')->chunkById(50, function ($oticas): void {
            foreach ($oticas as $otica) {
                $dados = [];

                if (! empty($otica->cnpj)) {
                    $dados['cnpj_hash'] = hash('sha256', $otica->cnpj);
                    $dados['cnpj'] = Crypt::encryptString($otica->cnpj);
                }

                foreach (['telefone', 'endereco'] as $campo) {
                    if (! empty($otica->{$campo})) {
                        $dados[$campo] = Crypt::encryptString($otica->{$campo});
                    }
                }

                if ($dados) {
                    DB::table('oticas')->where('id', $otica->id)->update($dados);
                }
            }
        });

        DB::table('pedidos')->orderBy('id')->chunkById(50, function ($pedidos): void {
            foreach ($pedidos as $pedido) {
                $dados = [];

                foreach (self::CAMPOS_PEDIDO as $campo) {
                    if (! empty($pedido->{$campo})) {
                        $dados[$campo] = Crypt::encryptString($pedido->{$campo});
                    }
                }

                if ($dados) {
                    DB::table('pedidos')->where('id', $pedido->id)->update($dados);
                }
            }
        });

        Schema::table('oticas', function (Blueprint $table): void {
            $table->unique('cnpj_hash');
        });
    }

    public function down(): void
    {
        Schema::table('oticas', function (Blueprint $table): void {
            $table->dropUnique(['cnpj_hash']);
        });

        DB::table('oticas')->orderBy('id')->chunkById(50, function ($oticas): void {
            foreach ($oticas as $otica) {
                $dados = [];

                foreach (self::CAMPOS_OTICA as $campo) {
                    if (! empty($otica->{$campo})) {
                        try {
                            $dados[$campo] = Crypt::decryptString($otica->{$campo});
                        } catch (\Throwable) {
                            // já estava em texto puro (não passou pela criptografia); mantém.
                        }
                    }
                }

                if ($dados) {
                    DB::table('oticas')->where('id', $otica->id)->update($dados);
                }
            }
        });

        DB::table('pedidos')->orderBy('id')->chunkById(50, function ($pedidos): void {
            foreach ($pedidos as $pedido) {
                $dados = [];

                foreach (self::CAMPOS_PEDIDO as $campo) {
                    if (! empty($pedido->{$campo})) {
                        try {
                            $dados[$campo] = Crypt::decryptString($pedido->{$campo});
                        } catch (\Throwable) {
                            // já estava em texto puro; mantém.
                        }
                    }
                }

                if ($dados) {
                    DB::table('pedidos')->where('id', $pedido->id)->update($dados);
                }
            }
        });

        DB::statement('ALTER TABLE oticas MODIFY telefone VARCHAR(30) NULL');
        DB::statement('ALTER TABLE oticas MODIFY endereco VARCHAR(255) NULL');
        DB::statement('ALTER TABLE oticas MODIFY cnpj VARCHAR(18) NULL');

        DB::statement('ALTER TABLE pedidos MODIFY cliente_nome VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pedidos MODIFY cliente_telefone VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pedidos MODIFY paciente_iniciais VARCHAR(10) NULL');
        DB::statement('ALTER TABLE pedidos MODIFY paciente_complemento VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pedidos MODIFY profissional_nome VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pedidos MODIFY profissional_crm VARCHAR(255) NULL');

        Schema::table('oticas', function (Blueprint $table): void {
            $table->dropColumn('cnpj_hash');
            $table->unique('cnpj');
        });
    }
};
