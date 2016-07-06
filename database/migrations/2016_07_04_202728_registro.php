<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Registro extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('registro')) {
            Schema::create('registro', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nombre');
                $table->string('cedula')->unique();
                $table->string('pasaporte');
                $table->string('nacionalidad');
                $table->string('departamento');
                $table->string('municipio');
                $table->string('telefono');
                $table->string('correo');
                $table->string('entidad');
                $table->string('ocupacion');
                $table->string('ruta');
                $table->string('interes');
                $table->string('n_entidad');
                $table->string('n_trabajo');
                $table->string('concurso');
                $table->string('t_producto');
                $table->string('n_recibo');
                $table->string('n_ponencia');
                $table->string('n_concurso');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('registro', function (Blueprint $table) {
            //
        });
    }

}
