<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TratamentoResource\Pages;
use App\Filament\Resources\TratamentoResource\RelationManagers\PrecosRelationManager;
use App\Models\Tratamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TratamentoResource extends Resource
{
    protected static ?string $model = Tratamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Tratamentos';

    protected static ?string $modelLabel = 'tratamento';

    protected static ?string $pluralModelLabel = 'tratamentos';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome do tratamento')
                        ->placeholder('Ex.: Antirreflexo, Fotossensível, Filtro de luz azul')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('ativo')
                        ->label('Disponível para pedidos')
                        ->default(true),
                    Forms\Components\Textarea::make('descricao')
                        ->label('Descrição')
                        ->rows(3)
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
                Tables\Columns\TextColumn::make('precos_count')
                    ->label('Tabelas com preço definido')
                    ->counts('precos')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('ativo')
                    ->boolean(),
            ])
            ->defaultSort('nome')
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Disponível'),
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

    public static function getRelations(): array
    {
        return [
            PrecosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTratamentos::route('/'),
            'create' => Pages\CreateTratamento::route('/create'),
            'edit' => Pages\EditTratamento::route('/{record}/edit'),
        ];
    }
}
