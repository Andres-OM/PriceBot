<?php

namespace App\Filament\Resources\TiendaResource\Pages;

use App\Filament\Resources\TiendaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTienda extends CreateRecord
{
    protected static string $resource = TiendaResource::class;
    protected function redirectTo(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
