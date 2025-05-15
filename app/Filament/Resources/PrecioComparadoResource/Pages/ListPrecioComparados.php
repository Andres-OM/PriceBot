<?php

namespace App\Filament\Resources\PrecioComparadoResource\Pages;

use App\Filament\Resources\PrecioComparadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrecioComparados extends ListRecords
{
    protected static string $resource = PrecioComparadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
