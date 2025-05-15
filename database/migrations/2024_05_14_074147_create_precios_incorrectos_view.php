<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePreciosIncorrectosView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_precios_incorrectos AS
            SELECT 
                p.ean,
                MAX(p.nombre) AS nombre,
                MAX(CASE WHEN p.tienda = 'Todofriki' THEN p.precio ELSE NULL END) AS precioTodofriki,
                MAX(CAST(REPLACE(pw.meta_value, ',', '.') AS DECIMAL(10,2))) AS precioBase
            FROM 
                precios p
            LEFT JOIN
                wpfrk_postmeta pm ON p.ean = pm.meta_value COLLATE utf8mb4_unicode_ci AND pm.meta_key = '_sku'
            LEFT JOIN
                wpfrk_postmeta pw ON pm.post_id = pw.post_id AND pw.meta_key = '_wcj_purchase_price'
            WHERE 
                p.fecha_creacion = CURDATE() AND
                p.tienda = 'Todofriki' AND
                p.precio < CAST(REPLACE(pw.meta_value, ',', '.') AS DECIMAL(10,2))
            GROUP BY 
                p.ean
            HAVING 
                precioTodofriki IS NOT NULL AND
                precioBase IS NOT NULL 
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_precios_incorrectos");
    }
}