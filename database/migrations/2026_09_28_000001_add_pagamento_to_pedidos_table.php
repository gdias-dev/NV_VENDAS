<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->boolean('pago')->default(false)->after('status');
            $table->timestamp('pago_em')->nullable()->after('pago');
            $table->string('forma_pagamento')->nullable()->after('pago_em');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['pago', 'pago_em', 'forma_pagamento']);
        });
    }
};
