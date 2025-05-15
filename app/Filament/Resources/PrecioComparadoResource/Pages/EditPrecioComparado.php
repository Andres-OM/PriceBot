<?php

namespace App\Filament\Resources\PrecioComparadoResource\Pages;

use App\Filament\Resources\PrecioComparadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrecioComparado extends EditRecord
{
    protected static string $resource = PrecioComparadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
