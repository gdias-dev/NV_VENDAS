<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PedidoPdfController extends Controller
{
    /**
     * PDF da ordem de serviço, para quem já está logado no painel
     * administrativo (guard "web" — o mesmo do Filament).
     */
    public function show(Pedido $pedido): Response
    {
        $pedido->load(['otica', 'lente', 'tratamentos', 'tabelaPreco']);

        return Pdf::loadView('pdf.ordem-servico', ['pedido' => $pedido])
            ->stream('pedido-'.$pedido->id.'.pdf');
    }
}
