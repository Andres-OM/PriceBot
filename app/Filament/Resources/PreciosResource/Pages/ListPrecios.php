<?php

namespace App\Filament\Resources\PreciosResource\Pages;

use App\Filament\Resources\PreciosResource;
use App\Filament\Resources\PreciosResource\Widgets\RegistrosStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PreciosResource\Widgets\PriceHistoryChart;

class ListPrecios extends ListRecords
{
    protected static string $resource = PreciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            RegistrosStatsOverview::class,
        ];
    }
    
    /*
    protected function getFooterWidgets(): array
    {
        return [
            PriceHistoryChart::class
        ];
    }
    */
}
