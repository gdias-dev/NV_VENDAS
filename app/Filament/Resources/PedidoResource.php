<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Lente;
use App\Models\Pedido;
use App\Models\Tratamento;
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
                // Sem ->searchable(): o nome do cliente agora é criptografado
                // no banco, então uma busca "LIKE" não encontra mais nada.
                // Pra achar um pedido, use o número (#id), a ótica ou o status.
                Tables\Columns\TextColumn::make('cliente_nome')
                    ->label('Cliente'),
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
                Tables\Actions\Action::make('editarPedido')
                    ->label('Editar pedido')
                    ->icon('heroicon-o-pencil-square')
                    ->color('gray')
                    ->modalHeading('Editar pedido')
                    ->modalWidth('3xl')
                    ->form(static::editarPedidoFormSchema())
                    ->fillForm(fn (Pedido $record): array => static::editarPedidoFillForm($record))
                    ->action(fn (Pedido $record, array $data) => static::editarPedidoSave($record, $data))
                    ->successNotificationTitle('Pedido atualizado'),
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

    /**
     * Formulário usado pela ação "Editar pedido" — tanto na listagem quanto
     * na tela de detalhe do pedido. Permite corrigir lente, tratamentos,
     * dados de montagem/armação e os valores, para casos em que a ótica
     * pediu errado ou o laboratório precisa ajustar algo depois de recebido.
     */
    public static function editarPedidoFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Lente e tratamentos')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('lente_id')
                        ->label('Lente')
                        ->options(fn (): array => Lente::where('ativo', true)->pluck('nome', 'id')->all())
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, $state): void {
                            $set('lente_nome', Lente::find($state)?->nome);
                        })
                        ->required(),
                    Forms\Components\Hidden::make('lente_nome'),
                    Forms\Components\Repeater::make('tratamentos')
                        ->label('Tratamentos')
                        ->columnSpanFull()
                        ->columns(2)
                        ->addActionLabel('Adicionar tratamento')
                        ->defaultItems(0)
                        ->schema([
                            Forms\Components\Select::make('tratamento_id')
                                ->label('Tratamento')
                                ->options(fn (): array => Tratamento::where('ativo', true)->pluck('nome', 'id')->all())
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, $state): void {
                                    $set('tratamento_nome', Tratamento::find($state)?->nome);
                                })
                                ->required(),
                            Forms\Components\Hidden::make('tratamento_nome'),
                            Forms\Components\TextInput::make('preco')
                                ->label('Preço')
                                ->numeric()
                                ->prefix('R$')
                                ->required(),
                        ]),
                ]),

            Forms\Components\Section::make('Montagem e armação')
                ->columns(2)
                ->schema([
                    Forms\Components\Toggle::make('com_montagem')
                        ->label('Com montagem')
                        ->live()
                        ->columnSpanFull(),
                    Forms\Components\Select::make('montagem_formato_armacao')
                        ->label('Formato da armação')
                        ->options(Pedido::FORMATOS_ARMACAO)
                        ->live()
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\FileUpload::make('montagem_foto_armacao')
                        ->label('Foto da armação')
                        ->image()
                        ->disk('public')
                        ->directory('armacoes')
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem') && $get('montagem_formato_armacao') === Pedido::FORMATO_UPLOAD),
                    Forms\Components\Select::make('montagem_clipon')
                        ->label('Clip-on')
                        ->options(Pedido::CLIPON_OPTIONS)
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\Toggle::make('montagem_enviar_armacao')
                        ->label('Ótica vai enviar a armação')
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_mva')->label('MVA')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_mha')->label('MHA')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_dma')->label('DMA')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_ponte')->label('Ponte')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_dpa')->label('DPA')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_diametro_od')->label('Diâmetro O.D.')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\TextInput::make('montagem_diametro_oe')->label('Diâmetro O.E.')->numeric()->suffix('mm')->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                    Forms\Components\Textarea::make('montagem_observacoes')
                        ->label('Observações da montagem')
                        ->rows(2)
                        ->columnSpanFull()
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('com_montagem')),
                ]),

            Forms\Components\Section::make('Valores')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('preco_lente_od')->label('Lente (OD)')->numeric()->prefix('R$')->required(),
                    Forms\Components\TextInput::make('preco_lente_oe')->label('Lente (OE)')->numeric()->prefix('R$')->required(),
                    Forms\Components\TextInput::make('preco_tratamentos')->label('Tratamentos')->numeric()->prefix('R$')->required(),
                    Forms\Components\TextInput::make('preco_montagem')->label('Montagem')->numeric()->prefix('R$')->required(),
                    Forms\Components\TextInput::make('preco_total')->label('Total')->numeric()->prefix('R$')->required()->columnSpanFull(),
                ]),
        ];
    }

    public static function editarPedidoFillForm(Pedido $record): array
    {
        return [
            'lente_id' => $record->lente_id,
            'lente_nome' => $record->lente_nome,
            'tratamentos' => $record->tratamentos->map(fn (\App\Models\PedidoTratamento $t): array => [
                'tratamento_id' => $t->tratamento_id,
                'tratamento_nome' => $t->tratamento_nome,
                'preco' => $t->preco,
            ])->all(),
            'com_montagem' => $record->com_montagem,
            'montagem_formato_armacao' => $record->montagem_formato_armacao,
            'montagem_foto_armacao' => $record->montagem_foto_armacao,
            'montagem_clipon' => $record->montagem_clipon,
            'montagem_enviar_armacao' => $record->montagem_enviar_armacao,
            'montagem_mva' => $record->montagem_mva,
            'montagem_mha' => $record->montagem_mha,
            'montagem_dma' => $record->montagem_dma,
            'montagem_ponte' => $record->montagem_ponte,
            'montagem_dpa' => $record->montagem_dpa,
            'montagem_diametro_od' => $record->montagem_diametro_od,
            'montagem_diametro_oe' => $record->montagem_diametro_oe,
            'montagem_observacoes' => $record->montagem_observacoes,
            'preco_lente_od' => $record->preco_lente_od,
            'preco_lente_oe' => $record->preco_lente_oe,
            'preco_tratamentos' => $record->preco_tratamentos,
            'preco_montagem' => $record->preco_montagem,
            'preco_total' => $record->preco_total,
        ];
    }

    public static function editarPedidoSave(Pedido $record, array $data): void
    {
        $record->update([
            'lente_id' => $data['lente_id'] ?? null,
            'lente_nome' => $data['lente_nome'] ?? null,
            'com_montagem' => (bool) ($data['com_montagem'] ?? false),
            'montagem_formato_armacao' => ($data['com_montagem'] ?? false) ? ($data['montagem_formato_armacao'] ?? null) : null,
            'montagem_foto_armacao' => ($data['com_montagem'] ?? false) ? ($data['montagem_foto_armacao'] ?? null) : null,
            'montagem_clipon' => ($data['com_montagem'] ?? false) ? ($data['montagem_clipon'] ?? null) : null,
            'montagem_enviar_armacao' => ($data['com_montagem'] ?? false) ? (bool) ($data['montagem_enviar_armacao'] ?? false) : false,
            'montagem_mva' => ($data['com_montagem'] ?? false) ? ($data['montagem_mva'] ?? null) : null,
            'montagem_mha' => ($data['com_montagem'] ?? false) ? ($data['montagem_mha'] ?? null) : null,
            'montagem_dma' => ($data['com_montagem'] ?? false) ? ($data['montagem_dma'] ?? null) : null,
            'montagem_ponte' => ($data['com_montagem'] ?? false) ? ($data['montagem_ponte'] ?? null) : null,
            'montagem_dpa' => ($data['com_montagem'] ?? false) ? ($data['montagem_dpa'] ?? null) : null,
            'montagem_diametro_od' => ($data['com_montagem'] ?? false) ? ($data['montagem_diametro_od'] ?? null) : null,
            'montagem_diametro_oe' => ($data['com_montagem'] ?? false) ? ($data['montagem_diametro_oe'] ?? null) : null,
            'montagem_observacoes' => ($data['com_montagem'] ?? false) ? ($data['montagem_observacoes'] ?? null) : null,
            'preco_lente_od' => $data['preco_lente_od'] ?? 0,
            'preco_lente_oe' => $data['preco_lente_oe'] ?? 0,
            'preco_tratamentos' => $data['preco_tratamentos'] ?? 0,
            'preco_montagem' => $data['preco_montagem'] ?? 0,
            'preco_total' => $data['preco_total'] ?? 0,
        ]);

        $record->tratamentos()->delete();

        foreach ($data['tratamentos'] ?? [] as $tratamento) {
            if (empty($tratamento['tratamento_id'])) {
                continue;
            }

            $record->tratamentos()->create([
                'tratamento_id' => $tratamento['tratamento_id'],
                'tratamento_nome' => $tratamento['tratamento_nome'] ?? Tratamento::find($tratamento['tratamento_id'])?->nome,
                'preco' => $tratamento['preco'] ?? 0,
            ]);
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidos::route('/'),
            'view' => Pages\ViewPedido::route('/{record}'),
        ];
    }
}
