<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Pedido;
use Filament\Infolists\Components\ImageEntry;
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
                Tables\Columns\IconColumn::make('pago')
                    ->label('Pago')
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('pago')
                    ->label('Pagamento'),
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
                Tables\Actions\Action::make('pagamento')
                    ->label(fn (Pedido $record): string => $record->pago ? 'Pagamento' : 'Registrar pagamento')
                    ->icon('heroicon-o-banknotes')
                    ->color(fn (Pedido $record): string => $record->pago ? 'success' : 'gray')
                    ->form([
                        Forms\Components\Toggle::make('pago')
                            ->label('Pago')
                            ->live(),
                        Forms\Components\Select::make('forma_pagamento')
                            ->label('Forma de pagamento')
                            ->options(Pedido::FORMAS_PAGAMENTO)
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('pago'))
                            ->required(fn (Forms\Get $get): bool => (bool) $get('pago')),
                        Forms\Components\DatePicker::make('pago_em')
                            ->label('Data do pagamento')
                            ->default(now())
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('pago')),
                    ])
                    ->fillForm(fn (Pedido $record): array => [
                        'pago' => $record->pago,
                        'forma_pagamento' => $record->forma_pagamento,
                        'pago_em' => $record->pago_em?->toDateString(),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $pago = (bool) ($data['pago'] ?? false);

                        $record->update([
                            'pago' => $pago,
                            'forma_pagamento' => $pago ? ($data['forma_pagamento'] ?? null) : null,
                            'pago_em' => $pago ? ($data['pago_em'] ?? now()->toDateString()) : null,
                        ]);
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

            InfolistSection::make('Armação')
                ->columns(2)
                ->visible(fn (Pedido $record): bool => $record->temDadosArmacao())
                ->schema([
                    TextEntry::make('montagem_formato_armacao')
                        ->label('Formato')
                        ->formatStateUsing(fn (?string $state): string => $state && $state !== Pedido::FORMATO_UPLOAD ? (Pedido::FORMATOS_ARMACAO[$state] ?? $state) : 'Foto enviada')
                        ->placeholder('—'),
                    TextEntry::make('montagem_clipon')
                        ->label('Clip-on')
                        ->formatStateUsing(fn (?string $state): string => $state ? (Pedido::CLIPON_OPTIONS[$state] ?? $state) : '—'),
                    TextEntry::make('montagem_mva')->label('MVA')->suffix(' mm')->placeholder('—'),
                    TextEntry::make('montagem_mha')->label('MHA')->suffix(' mm')->placeholder('—'),
                    TextEntry::make('montagem_dma')->label('DMA')->suffix(' mm')->placeholder('—'),
                    TextEntry::make('montagem_ponte')->label('Ponte')->suffix(' mm')->placeholder('—'),
                    TextEntry::make('montagem_dpa')->label('DPA')->suffix(' mm')->placeholder('—'),
                    TextEntry::make('diametro_estimado')
                        ->label('Diâmetro estimado')
                        ->state(fn (Pedido $record): string => ($record->montagem_diametro_od || $record->montagem_diametro_oe)
                            ? 'O.D. '.($record->montagem_diametro_od ?? '—').' mm · O.E. '.($record->montagem_diametro_oe ?? '—').' mm'
                            : '—'),
                    TextEntry::make('montagem_enviar_armacao')
                        ->label('Armação enviada para montagem')
                        ->formatStateUsing(fn (bool $state): string => $state ? 'Sim' : 'Não'),
                    ImageEntry::make('montagem_foto_armacao')
                        ->label('Foto da armação')
                        ->disk('public')
                        ->visible(fn (Pedido $record): bool => (bool) $record->montagem_foto_armacao)
                        ->columnSpanFull(),
                ]),

            InfolistSection::make('Paciente e profissional')
                ->columns(2)
                ->visible(fn (Pedido $record): bool => $record->temDadosProfissional())
                ->schema([
                    TextEntry::make('tipo_profissional')
                        ->label('Receita passada por')
                        ->formatStateUsing(fn (Pedido $record): string => $record->tipoProfissionalLabel()),
                    TextEntry::make('profissional_nome')->label('Nome do profissional')->placeholder('—'),
                    TextEntry::make('profissional_crm')
                        ->label('CRM')
                        ->formatStateUsing(fn (Pedido $record): string => $record->profissional_crm ? $record->profissional_uf_crm.' '.$record->profissional_crm : '—')
                        ->visible(fn (Pedido $record): bool => $record->tipo_profissional === Pedido::TIPO_PROFISSIONAL_MEDICO),
                    TextEntry::make('paciente_iniciais')->label('Iniciais do paciente')->placeholder('—'),
                    TextEntry::make('paciente_idade')->label('Idade do paciente')->suffix(' anos')->placeholder('—'),
                    TextEntry::make('paciente_complemento')->label('Complemento')->placeholder('—'),
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

            InfolistSection::make('Financeiro')
                ->columns(2)
                ->schema([
                    TextEntry::make('pago')
                        ->label('Situação')
                        ->badge()
                        ->formatStateUsing(fn (bool $state): string => $state ? 'Pago' : 'Pendente')
                        ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                    TextEntry::make('forma_pagamento')
                        ->label('Forma de pagamento')
                        ->formatStateUsing(fn (?string $state): string => $state ? (Pedido::FORMAS_PAGAMENTO[$state] ?? $state) : '—'),
                    TextEntry::make('pago_em')
                        ->label('Data do pagamento')
                        ->date('d/m/Y')
                        ->placeholder('—'),
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
