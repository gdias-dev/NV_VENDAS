<?php

namespace App\Filament\Pages;

use App\Models\Otica;
use App\Models\Pedido;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class RelatorioFinanceiro extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $navigationLabel = 'Relatório financeiro';

    protected static ?string $title = 'Relatório financeiro';

    protected static string $view = 'filament.pages.relatorio-financeiro';

    public string $dataInicio = '';

    public string $dataFim = '';

    public ?int $oticaId = null;

    public function mount(): void
    {
        $this->dataInicio = now()->startOfMonth()->toDateString();
        $this->dataFim = now()->toDateString();
    }

    /**
     * Consulta base do período/ótica filtrados. Pedidos cancelados não entram
     * no faturamento, mas continuam existindo e visíveis em "Pedidos".
     */
    protected function pedidosFiltrados()
    {
        return Pedido::query()
            ->whereDate('created_at', '>=', $this->dataInicio ?: now()->startOfMonth()->toDateString())
            ->whereDate('created_at', '<=', $this->dataFim ?: now()->toDateString())
            ->when($this->oticaId, fn ($query) => $query->where('otica_id', $this->oticaId))
            ->where('status', '!=', Pedido::STATUS_CANCELADO);
    }

    #[Computed]
    public function oticas()
    {
        return Otica::orderBy('nome_fantasia')->pluck('nome_fantasia', 'id');
    }

    #[Computed]
    public function resumo(): array
    {
        $pedidos = $this->pedidosFiltrados()->get();

        $faturado = (float) $pedidos->sum('preco_total');
        $recebido = (float) $pedidos->where('pago', true)->sum('preco_total');

        return [
            'quantidade' => $pedidos->count(),
            'faturado' => $faturado,
            'recebido' => $recebido,
            'a_receber' => $faturado - $recebido,
            'ticket_medio' => $pedidos->count() > 0 ? $faturado / $pedidos->count() : 0.0,
        ];
    }

    #[Computed]
    public function porOtica()
    {
        return $this->pedidosFiltrados()
            ->selectRaw('otica_id, count(*) as quantidade, sum(preco_total) as total')
            ->groupBy('otica_id')
            ->with('otica')
            ->orderByDesc('total')
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportarCsv')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn (): string => route('admin.relatorios.csv', [
                    'data_inicio' => $this->dataInicio,
                    'data_fim' => $this->dataFim,
                    'otica_id' => $this->oticaId,
                ]))
                ->openUrlInNewTab(),
        ];
    }
}
