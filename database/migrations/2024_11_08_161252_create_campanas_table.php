<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampanasTable extends Migration
{
    public function up()
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->date('fecha_inicio');
            $table->timestamps(); // Esto incluirá campos de fecha de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('campanas');
    }
}
