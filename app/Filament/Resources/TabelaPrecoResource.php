<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabelaPrecoResource\Pages;
use App\Models\TabelaPreco;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TabelaPrecoResource extends Resource
{
    protected static ?string $model = TabelaPreco::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Preços';

    protected static ?string $navigationLabel = 'Tabelas de preço';

    protected static ?string $modelLabel = 'tabela de preço';

    protected static ?string $pluralModelLabel = 'tabelas de preço';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome da tabela')
                        ->placeholder('Ex.: Padrão, Ótica Parceira, Rede XPTO')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('valor_montagem')
                        ->label('Valor da montagem')
                        ->helperText('Cobrado quando a ótica pede o óculos já montado.')
                        ->numeric()
                        ->prefix('R$')
                        ->required()
                        ->default(0)
                        ->columnSpan(1),
                    Forms\Components\Textarea::make('descricao')
                        ->label('Descrição / observações')
                        ->rows(2)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_default')
                        ->label('Usar como tabela padrão para novas óticas')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('valor_montagem')
                    ->label('Montagem')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('oticas_count')
                    ->label('Óticas')
                    ->counts('oticas')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Padrão')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nome')
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabelaPrecos::route('/'),
            'create' => Pages\CreateTabelaPreco::route('/create'),
            'edit' => Pages\EditTabelaPreco::route('/{record}/edit'),
        ];
    }
}
