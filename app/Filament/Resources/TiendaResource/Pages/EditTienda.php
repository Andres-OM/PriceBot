<?php

namespace App\Filament\Resources\TiendaResource\Pages;

use App\Filament\Resources\TiendaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTienda extends EditRecord
{
    protected static string $resource = TiendaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
