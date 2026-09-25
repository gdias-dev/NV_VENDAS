<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class FaturamentoPorDiaChart extends ChartWidget
{
    protected static ?string $heading = 'Faturamento — últimos 14 dias';

    protected function getData(): array
    {
        $dias = collect(range(13, 0))->map(fn (int $i): string => now()->subDays($i)->toDateString());

        $porDia = Pedido::query()
            ->selectRaw('date(created_at) as dia, sum(preco_total) as total')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->where('status', '!=', Pedido::STATUS_CANCELADO)
            ->groupBy('dia')
            ->pluck('total', 'dia');

        return [
            'datasets' => [
                [
                    'label' => 'Faturamento (R$)',
                    'data' => $dias->map(fn (string $dia): float => (float) ($porDia[$dia] ?? 0))->values(),
                    'backgroundColor' => '#3e4095',
                ],
            ],
            'labels' => $dias->map(fn (string $dia): string => Carbon::parse($dia)->format('d/m'))->values(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
