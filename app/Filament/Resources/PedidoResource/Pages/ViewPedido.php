<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\Pedido;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPedido extends ViewRecord
{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Ordem de serviço (PDF)')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->url(fn (Pedido $record): string => route('admin.pedidos.pdf', $record))
                ->openUrlInNewTab(),
        ];
    }
}
