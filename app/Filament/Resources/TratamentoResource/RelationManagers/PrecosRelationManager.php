<?php

namespace App\Filament\Resources\TratamentoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PrecosRelationManager extends RelationManager
{
    protected static string $relationship = 'precos';

    protected static ?string $title = 'Preço por tabela';

    protected static ?string $modelLabel = 'preço';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tabela_preco_id')
                ->label('Tabela de preço')
                ->relationship('tabelaPreco', 'nome')
                ->searchable()
                ->preload()
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('preco')
                ->label('Preço')
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
