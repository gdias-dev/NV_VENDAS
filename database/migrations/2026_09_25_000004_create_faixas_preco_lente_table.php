<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faixas_preco_lente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lente_id')->constrained('lentes')->cascadeOnDelete();
            $table->foreignId('tabela_preco_id')->constrained('tabelas_preco')->cascadeOnDelete();

            // Faixa dióptrica em que esta lente custa "preco". Valores em dioptrias (ex.: -6.00 a 0.00).
            $table->decimal('esferico_min', 5, 2);
            $table->decimal('esferico_max', 5, 2);
            $table->decimal('cilindrico_min', 5, 2)->nullable();
            $table->decimal('cilindrico_max', 5, 2)->nullable();

            $table->decimal('preco', 8, 2);
            $table->timestamps();

            $table->index(['lente_id', 'tabela_preco_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faixas_preco_lente');
    }
};
