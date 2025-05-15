<?php

namespace App\Exports;

use App\Models\Precio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;

class PreciosExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Precio::query();
    }

    public function headings(): array
    {
        return ['ID', 'EAN', 'Nombre', 'Tienda', 'Precio', 'Fecha de Creación'];
    }

    /**
     * @param $precio
     * @return array
     */
    public function map($precio): array
    {
        return [
            $precio->id,
            $precio->ean,
            $precio->nombre,
            $precio->tienda,
            $precio->precio,
            $precio->fecha_creacion->format('j-n-Y')
        ];
    }
}