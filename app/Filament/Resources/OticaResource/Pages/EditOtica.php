<?php

namespace App\Filament\Resources\OticaResource\Pages;

use App\Filament\Resources\OticaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOtica extends EditRecord
{
    protected static string $resource = OticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
