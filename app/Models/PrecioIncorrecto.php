<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioIncorrecto extends Model
{
    use HasFactory;

    public $timestamps = false;  
    protected $primaryKey = 'ean';
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $table = 'v_precios_incorrectos';

    protected $fillable = [
        'ean',
        'nombre',
        'precioTodofriki',
        'precioBase',
    ];


}