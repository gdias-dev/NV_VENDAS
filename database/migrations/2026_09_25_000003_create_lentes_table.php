<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            // visao_simples | multifocal | bifocal
            $table->string('tipo');
            $table->string('material')->nullable();
            $table->string('indice')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lentes');
    }
};
