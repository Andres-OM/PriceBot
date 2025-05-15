<?php

namespace App\Filament\Resources\PrecioIncorrectoResource\Pages;

use App\Filament\Resources\PrecioIncorrectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrecioIncorrecto extends EditRecord
{
    protected static string $resource = PrecioIncorrectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
