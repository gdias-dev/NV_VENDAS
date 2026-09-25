<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LenteResource\Pages;
use App\Filament\Resources\LenteResource\RelationManagers\FaixasPrecoRelationManager;
use App\Models\Lente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LenteResource extends Resource
{
    protected static ?string $model = Lente::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Lentes';

    protected static ?string $modelLabel = 'lente';

    protected static ?string $pluralModelLabel = 'lentes';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->label('Nome da lente')
                        ->placeholder('Ex.: CR-39 1.56, Multifocal Digital Premium')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(Lente::TIPOS)
                        ->required(),
                    Forms\Components\TextInput::make('material')
                        ->label('Material')
                        ->placeholder('Ex.: Resina, Policarbonato'),
                    Forms\Components\TextInput::make('indice')
                        ->label('Índice de refração')
                        ->placeholder('Ex.: 1.56, 1.61, 1.67'),
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
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Lente::TIPOS[$state] ?? $state),
                Tables\Columns\TextColumn::make('material')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('indice')
                    ->label('Índice')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('faixas_preco_count')
                    ->label('Faixas de preço')
                    ->counts('faixasPreco')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('ativo')
                    ->boolean(),
            ])
            ->defaultSort('nome')
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->options(Lente::TIPOS),
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
            FaixasPrecoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLentes::route('/'),
            'create' => Pages\CreateLente::route('/create'),
            'edit' => Pages\EditLente::route('/{record}/edit'),
        ];
    }
}
