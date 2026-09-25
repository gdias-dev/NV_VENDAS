<?php

namespace App\Filament\Resources\LenteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FaixasPrecoRelationManager extends RelationManager
{
    protected static string $relationship = 'faixasPreco';

    protected static ?string $title = 'Faixas de preço por grau';

    protected static ?string $modelLabel = 'faixa de preço';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tabela_preco_id')
                ->label('Tabela de preço')
                ->relationship('tabelaPreco', 'nome')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Fieldset::make('Faixa esférico (obrigatória)')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('esferico_min')
                        ->label('De (dioptrias)')
                        ->numeric()
                        ->step(0.25)
                        ->required(),
                    Forms\Components\TextInput::make('esferico_max')
                        ->label('Até (dioptrias)')
                        ->numeric()
                        ->step(0.25)
                        ->required(),
                ]),

            Forms\Components\Fieldset::make('Faixa cilíndrico (opcional)')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('cilindrico_min')
                        ->label('De (dioptrias)')
                        ->numeric()
                        ->step(0.25),
                    Forms\Components\TextInput::make('cilindrico_max')
                        ->label('Até (dioptrias)')
                        ->numeric()
                        ->step(0.25),
                ]),

            Forms\Components\TextInput::make('preco')
                ->label('Preço nesta faixa')
                ->numeric()
                ->prefix('R$')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('tabelaPreco.nome')
                    ->label('Tabela de preço')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('faixa_esferico')
                    ->label('Esférico')
                    ->state(fn ($record): string => number_format((float) $record->esferico_min, 2).' a '.number_format((float) $record->esferico_max, 2)),
                Tables\Columns\TextColumn::make('faixa_cilindrico')
                    ->label('Cilíndrico')
                    ->state(function ($record): string {
                        if ($record->cilindrico_min === null && $record->cilindrico_max === null) {
                            return '—';
                        }

                        return number_format((float) $record->cilindrico_min, 2).' a '.number_format((float) $record->cilindrico_max, 2);
                    }),
                Tables\Columns\TextColumn::make('preco')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
