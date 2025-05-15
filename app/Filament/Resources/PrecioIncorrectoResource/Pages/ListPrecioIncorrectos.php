<?php

namespace App\Filament\Resources\PrecioIncorrectoResource\Pages;

use App\Filament\Resources\PrecioIncorrectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrecioIncorrectos extends ListRecords
{
    protected static string $resource = PrecioIncorrectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
