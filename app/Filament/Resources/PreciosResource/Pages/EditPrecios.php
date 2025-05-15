<?php

namespace App\Filament\Resources\PreciosResource\Pages;

use App\Filament\Resources\PreciosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrecios extends EditRecord
{
    protected static string $resource = PreciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
