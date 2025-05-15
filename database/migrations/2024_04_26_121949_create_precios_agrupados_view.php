<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePreciosAgrupadosView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_precios_agrupados AS
            SELECT 
                ean,
                MAX(nombre) AS nombre,
                GROUP_CONCAT(DISTINCT CONCAT(tienda, ': ', FORMAT(precio, 2)) SEPARATOR ', ') AS precios_concatenados,
                fecha_creacion
            FROM precios
            GROUP BY ean, fecha_creacion
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS v_precios_agrupados');
    }
}