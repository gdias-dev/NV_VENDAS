<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('otica_id')->constrained('oticas')->cascadeOnDelete();

            // Dados do cliente final (quem vai usar o óculos).
            $table->string('cliente_nome');
            $table->string('cliente_telefone')->nullable();

            // A lente escolhida. Guardamos o nome também "congelado" (lente_nome),
            // porque o catálogo pode mudar/apagar a lente depois e o pedido não
            // pode perder essa informação histórica.
            $table->foreignId('lente_id')->nullable()->constrained('lentes')->nullOnDelete();
            $table->string('lente_nome')->nullable();

            // Tabela de preço usada no momento do pedido (também "congelada" pelo
            // mesmo motivo: a ótica pode mudar de tabela depois).
            $table->foreignId('tabela_preco_id')->nullable()->constrained('tabelas_preco')->nullOnDelete();

            // Receita: olho direito (OD) e olho esquerdo (OE).
            $table->decimal('od_esferico', 5, 2)->nullable();
            $table->decimal('od_cilindrico', 5, 2)->nullable();
            $table->unsignedSmallInteger('od_eixo')->nullable();
            $table->decimal('od_adicao', 5, 2)->nullable();

            $table->decimal('oe_esferico', 5, 2)->nullable();
            $table->decimal('oe_cilindrico', 5, 2)->nullable();
            $table->unsignedSmallInteger('oe_eixo')->nullable();
            $table->decimal('oe_adicao', 5, 2)->nullable();

            // Montagem (opcional).
            $table->boolean('com_montagem')->default(false);
            $table->text('montagem_observacoes')->nullable();

            // Preços calculados automaticamente no momento do pedido.
            $table->decimal('preco_lente_od', 10, 2)->default(0);
            $table->decimal('preco_lente_oe', 10, 2)->default(0);
            $table->decimal('preco_tratamentos', 10, 2)->default(0);
            $table->decimal('preco_montagem', 10, 2)->default(0);
            $table->decimal('preco_total', 10, 2)->default(0);

            $table->string('status')->default('recebido');
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
