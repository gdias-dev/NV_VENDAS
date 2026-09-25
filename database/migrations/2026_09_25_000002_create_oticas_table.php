<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oticas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia');
            $table->string('razao_social')->nullable();
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('email');
            $table->string('telefone', 30)->nullable();
            $table->string('endereco')->nullable();
            $table->foreignId('tabela_preco_id')->constrained('tabelas_preco')->restrictOnDelete();
            // pendente: aguardando aprovação do laboratório. aprovada: pode operar (login na Etapa 3).
            // bloqueada: acesso suspenso.
            $table->string('status')->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oticas');
    }
};
