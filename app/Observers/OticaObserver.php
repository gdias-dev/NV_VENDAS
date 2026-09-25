<?php

namespace App\Observers;

use App\Mail\CadastroOticaAprovado;
use App\Models\Otica;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OticaObserver
{
    /**
     * Avisa a ótica por e-mail assim que o status muda para "aprovada"
     * (seja lá quem tenha feito a mudança no painel administrativo).
     */
    public function updated(Otica $otica): void
    {
        if (! $otica->wasChanged('status') || ! $otica->isAprovada()) {
            return;
        }

        try {
            Mail::to($otica->email)->send(new CadastroOticaAprovado($otica));
        } catch (Throwable $e) {
            Log::error('Falha ao avisar a ótica sobre a aprovação: '.$e->getMessage(), [
                'otica_id' => $otica->id,
            ]);
        }
    }
}
