<?php

namespace App\Filament\Resources\LenteResource\Pages;

use App\Filament\Resources\LenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLentes extends ListRecords
{
    protected static string $resource = LenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
