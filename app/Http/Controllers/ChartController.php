<?php
namespace App\Http\Controllers;

use App\Models\PrecioAgrupado;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function show($ean)
    {
        $priceData = PrecioAgrupado::where('ean', $ean)
                        ->orderBy('fecha_creacion')
                        ->get();

        $labels = [];
        $datasets = [];

        // Definimos una paleta de colores
        $colorPalette = [
            'rgba(255, 99, 132, 0.5)', // rojo
            'rgba(54, 162, 235, 0.5)', // azul
            'rgba(255, 206, 86, 0.5)', // amarillo
            'rgba(75, 192, 192, 0.5)', // verde agua
            'rgba(153, 102, 255, 0.5)', // morado
            'rgba(255, 159, 64, 0.5)', // naranja
            'rgba(233, 30, 99, 0.5)', // rosa fuerte
            'rgba(156, 39, 176, 0.5)', // púrpura
            'rgba(0, 150, 136, 0.5)', // teal
            'rgba(103, 58, 183, 0.5)' // índigo
        ];

         // Inicializar todos los conjuntos de datos vacíos
         foreach ($priceData as $data) {
            $labels[] = $data->fecha_creacion->format('Y-m-d');
            $prices = explode(', ', $data->precios_concatenados);
            foreach ($prices as $price) {
                list($tienda, $precio) = explode(': ', $price);
                if (!isset($datasets[$tienda])) {
                    $datasets[$tienda] = [
                        'label' => $tienda,
                        'data' => [],
                        'backgroundColor' => '',
                        'borderColor' => '',
                    ];
                }
            }
        }

        // Rellenar todos los datasets con null para cada fecha inicialmente
        foreach ($datasets as &$dataset) {
            $dataset['data'] = array_fill(0, count($labels), null);
        }

        // Rellenar los datasets con los precios reales
        foreach ($priceData as $index => $data) {
            $prices = explode(', ', $data->precios_concatenados);
            foreach ($prices as $price) {
                list($tienda, $precio) = explode(': ', $price);
                $datasets[$tienda]['data'][$index] = floatval(trim($precio));
            }
        }

        // Asignar colores a cada dataset
        $index = 0;
        foreach ($datasets as &$dataset) {
            $colorIndex = $index % count($colorPalette); // Asegura la rotación de colores
            $dataset['backgroundColor'] = $colorPalette[$colorIndex];
            $dataset['borderColor'] = $colorPalette[$colorIndex];
            $index++;
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => array_values($datasets),
        ];

        return view('filament.resources.precios-resource.widgets.price-history-chart', compact('chartData'));
    }
}