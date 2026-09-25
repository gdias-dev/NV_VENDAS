<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_tratamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();

            // Assim como a lente, o tratamento também é "congelado" (nome e preço
            // no momento do pedido), para o histórico não mudar se o catálogo mudar.
            $table->foreignId('tratamento_id')->nullable()->constrained('tratamentos')->nullOnDelete();
            $table->string('tratamento_nome');
            $table->decimal('preco', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_tratamento');
    }
};
