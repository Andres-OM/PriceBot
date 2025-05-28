<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePreciosComparadosView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW v_precios_comparados AS
        SELECT 
            p.ean,
            MAX(p.nombre) AS nombre,
            MAX(CASE WHEN p.tienda = 'Pricebot' THEN p.precio ELSE NULL END) AS precioPricebot,
            MIN(p.precio) AS precioMasBajo,
            (
                SELECT tienda
                FROM precios p2
                WHERE p2.ean = p.ean
                  AND p2.precio = MIN(p.precio)
                LIMIT 1
            ) AS tienda,
            COALESCE(MAX(CAST(REPLACE(pw.meta_value, ',', '.') AS DECIMAL(10,2))), 'N/A') AS precioBase
        FROM 
            precios p
		LEFT JOIN
                wpfrk_postmeta pm ON p.ean = pm.meta_value COLLATE utf8mb4_unicode_ci AND pm.meta_key = '_sku'
        LEFT JOIN
            wpfrk_postmeta pw ON pm.post_id = pw.post_id AND pw.meta_key = '_wcj_purchase_price'
        WHERE 
            p.fecha_creacion = CURDATE()
        GROUP BY 
            p.ean
        HAVING 
            precioPricebot > precioMasBajo;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_precios_comparados");
    }
}