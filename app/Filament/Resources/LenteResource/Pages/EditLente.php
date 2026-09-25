<?php

namespace App\Filament\Resources\LenteResource\Pages;

use App\Filament\Resources\LenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLente extends EditRecord
{
    protected static string $resource = LenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
