<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;



class PrecioAgrupado extends Model
{
    use HasFactory;
    protected $table = 'v_precios_agrupados'; 
    protected $primaryKey = 'ean';
    public $incrementing = false; 
    protected $keyType = 'string'; 
    public $timestamps = false;

    protected $fillable = ['ean', 'nombre', 'precios_concatenados', 'fecha_creacion'];

    protected $dates = ['fecha_creacion'];
    public function getLastPriceModificationAttribute()
    {
    $currentPrices = collect(explode(', ', $this->precios_concatenados))->mapWithKeys(function ($item) {
        [$tienda, $precio] = explode(': ', $item);
        return [$tienda => floatval($precio)];
    });

    $previousRecords = self::where('ean', $this->ean)
                            ->where('fecha_creacion', '<', $this->fecha_creacion)
                            ->orderBy('fecha_creacion', 'desc')
                            ->get();

    $htmlOutput = ''; 

    foreach ($previousRecords as $record) {
        $pastPrices = collect(explode(', ', $record->precios_concatenados))->mapWithKeys(function ($item) {
            [$tienda, $precio] = explode(': ', $item);
            return [$tienda => floatval($precio)];
        });

        foreach ($currentPrices as $store => $currentPrice) {
            if (isset($pastPrices[$store]) && $pastPrices[$store] != $currentPrice) {
                $date = new DateTime($record->fecha_creacion);
                $dateFormatted = $date->format('d-m-Y');
                $htmlOutput .= "<strong>{$store}</strong>: {$pastPrices[$store]}€ ({$dateFormatted})<br>";
            }
        }
        if (!empty($htmlOutput)) {
            break;
        }
    }

    return $htmlOutput ?: 'No se encontraron cambios.'; 
}

}
