<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioComparado extends Model
{
    protected $table = 'v_precios_comparados';
    public $timestamps = false;  
    protected $primaryKey = 'ean';
    public $incrementing = false; 
    protected $keyType = 'string'; 
}
