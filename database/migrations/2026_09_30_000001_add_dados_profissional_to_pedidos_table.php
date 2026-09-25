<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('tipo_profissional')->default('medico')->after('oe_adicao');
            $table->string('profissional_nome')->nullable()->after('tipo_profissional');
            $table->string('profissional_uf_crm', 2)->nullable()->after('profissional_nome');
            $table->string('profissional_crm')->nullable()->after('profissional_uf_crm');
            $table->string('paciente_iniciais', 10)->nullable()->after('profissional_crm');
            $table->unsignedTinyInteger('paciente_idade')->nullable()->after('paciente_iniciais');
            $table->string('paciente_complemento')->nullable()->after('paciente_idade');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_profissional',
                'profissional_nome',
                'profissional_uf_crm',
                'profissional_crm',
                'paciente_iniciais',
                'paciente_idade',
                'paciente_complemento',
            ]);
        });
    }
};
