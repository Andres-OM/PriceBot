<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'precios';

    protected $fillable = [
        'ean',
        'nombre',
        'tienda',
        'precio',
        'fecha_creacion',
    ];

    public $timestamps = false; 
    
    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_creacion' => 'date'
    ];
    
}
