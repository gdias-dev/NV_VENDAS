<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RelatorioFinanceiroController extends Controller
{
    public function csv(Request $request): StreamedResponse
    {
        $dataInicio = $request->query('data_inicio') ?: now()->startOfMonth()->toDateString();
        $dataFim = $request->query('data_fim') ?: now()->toDateString();
        $oticaId = $request->query('otica_id');

        $pedidos = Pedido::query()
            ->whereDate('created_at', '>=', $dataInicio)
            ->whereDate('created_at', '<=', $dataFim)
            ->when($oticaId, fn ($query) => $query->where('otica_id', $oticaId))
            ->where('status', '!=', Pedido::STATUS_CANCELADO)
            ->with('otica')
            ->orderBy('created_at')
            ->get();

        $nomeArquivo = 'relatorio-financeiro-'.$dataInicio.'-a-'.$dataFim.'.csv';

        return response()->streamDownload(function () use ($pedidos) {
            $saida = fopen('php://output', 'w');

            // BOM, para o Excel abrir os acentos certinho.
            fwrite($saida, "\xEF\xBB\xBF");

            fputcsv($saida, ['Pedido', 'Data', 'Ótica', 'Cliente', 'Status', 'Pago', 'Forma de pagamento', 'Valor'], ';');

            foreach ($pedidos as $pedido) {
                fputcsv($saida, [
                    $pedido->id,
                    $pedido->created_at->format('d/m/Y H:i'),
                    $pedido->otica?->nome_fantasia,
                    $pedido->cliente_nome,
                    $pedido->statusLabel(),
                    $pedido->pago ? 'Sim' : 'Não',
                    $pedido->formaPagamentoLabel() ?? '—',
                    number_format((float) $pedido->preco_total, 2, ',', '.'),
                ], ';');
            }

            fclose($saida);
        }, $nomeArquivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
