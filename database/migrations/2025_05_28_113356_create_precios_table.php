<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->string('ean', 13)->nullable();
            $table->string('nombre')->nullable();
            $table->string('tienda')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->date('fecha_creacion');

            $table->unique(['ean', 'tienda', 'fecha_creacion'], 'idx_unique_ean_fecha_tienda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios');
    }
};
