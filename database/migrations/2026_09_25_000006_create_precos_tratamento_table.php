<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('precos_tratamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamento_id')->constrained('tratamentos')->cascadeOnDelete();
            $table->foreignId('tabela_preco_id')->constrained('tabelas_preco')->cascadeOnDelete();
            $table->decimal('preco', 8, 2);
            $table->timestamps();

            $table->unique(['tratamento_id', 'tabela_preco_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('precos_tratamento');
    }
};
