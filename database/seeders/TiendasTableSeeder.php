<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TiendasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tiendas')->insert([
            ['nombre' => 'Amazon', 'URL' => 'https://www.amazon.es/s?k=', 'nombrePrecio' => 'a-price'],
            ['nombre' => 'El Corte Inglés', 'URL' => 'https://www.elcorteingles.es/search/?s=', 'nombrePrecio' => 'price-unit--normal'],
            ['nombre' => 'ToysRUs', 'URL' => 'https://www.toysrus.es/search?q=', 'nombrePrecio' => 'price'],
            ['nombre' => 'Comicstores', 'URL' => 'https://comicstores.es/busqueda/listaLibros.php?tipoBus=full&palabrasBusqueda=', 'nombrePrecio' => 'p.precio strong'],
        ]);
    }
}
