<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('montagem_formato_armacao')->nullable()->after('montagem_observacoes');
            $table->string('montagem_foto_armacao')->nullable()->after('montagem_formato_armacao');
            $table->decimal('montagem_mva', 5, 2)->nullable()->after('montagem_foto_armacao');
            $table->decimal('montagem_mha', 5, 2)->nullable()->after('montagem_mva');
            $table->decimal('montagem_dma', 5, 2)->nullable()->after('montagem_mha');
            $table->decimal('montagem_ponte', 5, 2)->nullable()->after('montagem_dma');
            $table->decimal('montagem_dpa', 5, 2)->nullable()->after('montagem_ponte');
            $table->decimal('montagem_diametro_od', 5, 2)->nullable()->after('montagem_dpa');
            $table->decimal('montagem_diametro_oe', 5, 2)->nullable()->after('montagem_diametro_od');
            $table->string('montagem_clipon')->nullable()->after('montagem_diametro_oe');
            $table->boolean('montagem_enviar_armacao')->default(false)->after('montagem_clipon');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'montagem_formato_armacao',
                'montagem_foto_armacao',
                'montagem_mva',
                'montagem_mha',
                'montagem_dma',
                'montagem_ponte',
                'montagem_dpa',
                'montagem_diametro_od',
                'montagem_diametro_oe',
                'montagem_clipon',
                'montagem_enviar_armacao',
            ]);
        });
    }
};
