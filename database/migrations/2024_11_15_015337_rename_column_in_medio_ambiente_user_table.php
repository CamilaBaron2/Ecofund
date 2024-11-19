<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInMedioAmbienteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medioAmbienteUser', function (Blueprint $table) {
            $table->renameColumn('plain_password', 'hash_password'); // Cambia 'plain_password' por el nombre actual y 'hash_password' por el nuevo nombre
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medioAmbienteUser', function (Blueprint $table) {
            $table->renameColumn('hash_password', 'plain_password'); // Revertimos el cambio al nombre original
        });
    }
}
