<?php

namespace App\Filament\Resources\OticaResource\Pages;

use App\Filament\Resources\OticaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOticas extends ListRecords
{
    protected static string $resource = OticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
