<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_reciclajes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReciclajesTable extends Migration
{
    public function up()
    {
        Schema::create('reciclajes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->date('fecha_inicio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reciclajes');
    }
}

