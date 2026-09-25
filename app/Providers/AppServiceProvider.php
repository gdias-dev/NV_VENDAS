<?php

namespace App\Providers;

use App\Models\Otica;
use App\Models\Pedido;
use App\Observers\OticaObserver;
use App\Observers\PedidoObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Compatibilidade com versões antigas do MySQL/MariaDB (índices em utf8mb4)
        Schema::defaultStringLength(191);

        // Avisa a ótica por e-mail quando o cadastro é aprovado.
        Otica::observe(OticaObserver::class);

        // Avisa a ótica por e-mail quando o status de um pedido muda.
        Pedido::observe(PedidoObserver::class);

        // Link do WhatsApp disponível em todas as telas ($whatsappUrl). Null se não configurado.
        View::composer('*', function ($view) {
            $numero = preg_replace('/\D/', '', (string) config('nova.whatsapp'));

            $view->with(
                'whatsappUrl',
                $numero
                    ? 'https://wa.me/'.$numero.'?text='.rawurlencode('Olá! Gostaria de falar com o laboratório Nova Varonil.')
                    : null
            );
        });
    }
}
