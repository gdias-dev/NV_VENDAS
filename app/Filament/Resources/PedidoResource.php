<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Pedido;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Pedidos';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'pedido';

    protected static ?string $pluralModelLabel = 'pedidos';

    protected static ?int $navigationSort = 1;

    // Pedidos só nascem pelo portal das óticas: o painel não cria nem edita
    // o pedido em si, só acompanha e muda o status.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pendentes = static::getModel()::whereIn('status', [
            Pedido::STATUS_RECEBIDO,
            Pedido::STATUS_PRODUCAO,
        ])->count();

        return $pendentes > 0 ? (string) $pendentes : null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Pedido')
                    ->formatStateUsing(fn (int $state): string => '#'.$state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('otica.nome_fantasia')
                    ->label('Ótica')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente_nome')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lente_nome')
                    ->label('Lente')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Pedido::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Pedido::STATUS_ENTREGUE => 'success',
                        Pedido::STATUS_CANCELADO => 'danger',
                        Pedido::STATUS_PRODUCAO, Pedido::STATUS_EXPEDICAO => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('preco_total')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recebido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Pedido::STATUSES),
                Tables\Filters\SelectFilter::make('otica_id')
                    ->label('Ótica')
                    ->relationship('otica', 'nome_fantasia')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn (Pedido $record): string => route('admin.pedidos.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('mudarStatus')
                    ->label('Mudar status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Pedido::STATUSES)
                            ->required(),
                        Forms\Components\Textarea::make('observacoes')
                            ->label('Observações (a ótica não vê isso, é só interno)')
                            ->rows(3),
                    ])
                    ->fillForm(fn (Pedido $record): array => [
                        'status' => $record->status,
                        'observacoes' => $record->observacoes,
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $record->update($data);
                    }),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfolistSection::make('Pedido')
                ->columns(2)
                ->schema([
                    TextEntry::make('id')->label('Número')->formatStateUsing(fn (int $state): string => '#'.$state),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => Pedido::STATUSES[$state] ?? $state)
                        ->color(fn (string $state): string => match ($state) {
                            Pedido::STATUS_ENTREGUE => 'success',
                            Pedido::STATUS_CANCELADO => 'danger',
                            Pedido::STATUS_PRODUCAO, Pedido::STATUS_EXPEDICAO => 'info',
                            default => 'warning',
                        }),
                    TextEntry::make('otica.nome_fantasia')->label('Ótica'),
                    TextEntry::make('created_at')->label('Recebido em')->dateTime('d/m/Y H:i'),
                    TextEntry::make('cliente_nome')->label('Cliente'),
                    TextEntry::make('cliente_telefone')->label('Telefone')->placeholder('—'),
                ]),

            InfolistSection::make('Receita')
                ->columns(2)
                ->schema([
                    TextEntry::make('od_resumo')
                        ->label('Olho direito (OD)')
                        ->state(fn (Pedido $record): string => sprintf(
                            'Esf. %s / Cil. %s / Eixo %s / Ad. %s',
                            $record->od_esferico ?? '—',
                            $record->od_cilindrico ?? '—',
                            $record->od_eixo ?? '—',
                            $record->od_adicao ?? '—',
                        )),
                    TextEntry::make('oe_resumo')
                        ->label('Olho esquerdo (OE)')
                        ->state(fn (Pedido $record): string => sprintf(
                            'Esf. %s / Cil. %s / Eixo %s / Ad. %s',
                            $record->oe_esferico ?? '—',
                            $record->oe_cilindrico ?? '—',
                            $record->oe_eixo ?? '—',
                            $record->oe_adicao ?? '—',
                        )),
                ]),

            InfolistSection::make('Lente, tratamentos e montagem')
                ->columns(2)
                ->schema([
                    TextEntry::make('lente_nome')->label('Lente')->placeholder('—'),
                    TextEntry::make('com_montagem')->label('Montagem')->formatStateUsing(fn (bool $state): string => $state ? 'Sim' : 'Não'),
                    TextEntry::make('montagem_observacoes')->label('Observações da montagem')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('tratamentos.tratamento_nome')
                        ->label('Tratamentos')
                        ->listWithLineBreaks()
                        ->placeholder('Nenhum')
                        ->columnSpanFull(),
                ]),

            InfolistSection::make('Valores')
                ->columns(2)
                ->schema([
                    TextEntry::make('preco_lente_od')->label('Lente (OD)')->money('BRL'),
                    TextEntry::make('preco_lente_oe')->label('Lente (OE)')->money('BRL'),
                    TextEntry::make('preco_tratamentos')->label('Tratamentos')->money('BRL'),
                    TextEntry::make('preco_montagem')->label('Montagem')->money('BRL'),
                    TextEntry::make('preco_total')->label('Total')->money('BRL')->weight('bold')->columnSpanFull(),
                ]),

            InfolistSection::make('Observações internas')
                ->schema([
                    TextEntry::make('observacoes')->hiddenLabel()->placeholder('Nenhuma observação registrada.'),
                ])
                ->visible(fn (Pedido $record): bool => filled($record->observacoes)),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidos::route('/'),
            'view' => Pages\ViewPedido::route('/{record}'),
        ];
    }
}
