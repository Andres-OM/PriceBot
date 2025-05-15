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
            ['nombre' => 'El Corte Inglés', 'URL' => 'https://www.elcorteingles.es/search/?s=', 'nombrePrecio' => 'integer-price'],
            ['nombre' => 'Carrefour', 'URL' => 'https://www.carrefour.es/?gad_source=1&gclid=Cj0KCQjwlN6wBhCcARIsAKZvD5gVbe561LUH0P90cj3LdEL66NjoWzLm1iU2nFnhOChpMQhY6vC1lAAaAtLEEALw_wcB&gclsrc=aw.ds&q=', 'nombrePrecio' => 'ebx-result-price__value'],
            ['nombre' => 'ToysRUs', 'URL' => 'https://www.toysrus.es/search?q=', 'nombrePrecio' => 'price'],
            ['nombre' => 'Dynos', 'URL' => 'https://www.dynos.es/search?q=', 'nombrePrecio' => 'actual-price'],
            ['nombre' => 'Comicstores', 'URL' => 'https://comicstores.es/busqueda/listaLibros.php?tipoBus=full&palabrasBusqueda=', 'nombrePrecio' => 'p.precio strong'],
            ['nombre' => 'Game', 'URL' => 'https://www.game.es/buscar/', 'nombrePrecio' => 'buy--price']
        ]);
    }
}
