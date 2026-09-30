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
            Actions\Action::make('editarPedido')
                ->label('Editar pedido')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->modalHeading('Editar pedido')
                ->modalWidth('3xl')
                ->form(PedidoResource::editarPedidoFormSchema())
                ->fillForm(fn (Pedido $record): array => PedidoResource::editarPedidoFillForm($record))
                ->action(function (Pedido $record, array $data): void {
                    PedidoResource::editarPedidoSave($record, $data);
                })
                ->successNotificationTitle('Pedido atualizado')
                ->successRedirectUrl(fn (Pedido $record): string => PedidoResource::getUrl('view', ['record' => $record])),
        ];
    }
}
