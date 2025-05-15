<?php

namespace App\Filament\Resources\PreciosResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PrecioAgrupado;
use Carbon\Carbon;


class RegistrosStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        return [
            Stat::make('Total registros', PrecioAgrupado::count()),
            Stat::make('Registros hoy', PrecioAgrupado::whereDate('fecha_creacion', $today)->count()),
        ];
    }
}
