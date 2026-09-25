<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OticaResource\Pages;
use App\Models\Otica;
use App\Models\TabelaPreco;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class OticaResource extends Resource
{
    protected static ?string $model = Otica::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Óticas';

    protected static ?string $navigationLabel = 'Óticas cadastradas';

    protected static ?string $modelLabel = 'ótica';

    protected static ?string $pluralModelLabel = 'óticas';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados da ótica')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nome_fantasia')
                        ->label('Nome fantasia')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('razao_social')
                        ->label('Razão social')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->mask('99.999.999/9999-99')
                        ->maxLength(18),
                    Forms\Components\TextInput::make('email')
                        ->label('E-mail')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('telefone')
                        ->label('Telefone / WhatsApp')
                        ->tel()
                        ->maxLength(30),
                    Forms\Components\TextInput::make('endereco')
                        ->label('Endereço')
                        ->maxLength(255),
                ]),
            Forms\Components\Section::make('Comercial')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('tabela_preco_id')
                        ->label('Tabela de preço')
                        ->relationship('tabelaPreco', 'nome')
                        ->default(fn () => TabelaPreco::where('is_default', true)->first()?->id)
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Status do cadastro')
                        ->options(Otica::STATUSES)
                        ->default(Otica::STATUS_PENDENTE)
                        ->required(),
                    Forms\Components\Textarea::make('observacoes')
                        ->label('Observações internas')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Acesso ao portal')
                ->description('A própria ótica define sua senha ao se cadastrar pelo site. Use este campo só para criar um acesso manualmente ou resetar uma senha esquecida.')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('Senha')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->helperText('Deixe em branco para manter a senha atual.')
                        ->maxLength(255),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_fantasia')
                    ->label('Ótica')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Otica $record): ?string => $record->cnpj),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('telefone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tabelaPreco.nome')
                    ->label('Tabela de preço')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Otica::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Otica::STATUS_APROVADA => 'success',
                        Otica::STATUS_BLOQUEADA => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrada em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nome_fantasia')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Otica::STATUSES),
                Tables\Filters\SelectFilter::make('tabela_preco_id')
                    ->label('Tabela de preço')
                    ->relationship('tabelaPreco', 'nome'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOticas::route('/'),
            'create' => Pages\CreateOtica::route('/create'),
            'edit' => Pages\EditOtica::route('/{record}/edit'),
        ];
    }
}
