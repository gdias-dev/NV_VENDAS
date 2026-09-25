<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FaturamentoOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $pedidosMes = Pedido::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('status', '!=', Pedido::STATUS_CANCELADO)
            ->get();

        $faturado = (float) $pedidosMes->sum('preco_total');
        $recebido = (float) $pedidosMes->where('pago', true)->sum('preco_total');
        $aReceber = $faturado - $recebido;

        return [
            Stat::make('Faturado no mês', 'R$ '.number_format($faturado, 2, ',', '.'))
                ->description($pedidosMes->count().' pedidos (sem contar cancelados)')
                ->color('info'),
            Stat::make('Recebido no mês', 'R$ '.number_format($recebido, 2, ',', '.'))
                ->color('success'),
            Stat::make('A receber no mês', 'R$ '.number_format($aReceber, 2, ',', '.'))
                ->color('warning'),
        ];
    }
}
