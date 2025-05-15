<?php

namespace App\Filament\Resources\PreciosResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\PrecioAgrupado;
use Illuminate\Contracts\View\View;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;


class PriceHistoryChart extends ChartWidget
{

    protected static ?string $heading = 'Historial de Precios';

    public $ean;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Ejemplo Tienda 1',
                    'data' => [10, 20, 30, 40, 50, 60],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Ejemplo Tienda 2',
                    'data' => [15, 25, 35, 45, 55, 65],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        ];
    }
}

