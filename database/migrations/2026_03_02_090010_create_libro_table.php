<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('libro', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor');
            $table->integer('anho_publicacion');
            $table->string('genero')->nullable();
            $table->string('localizacion')->nullable();
            $table->string('editorial')->default('desconocida');
            $table->integer('paginas');
            $table->string('calificacion')->nullable()->default(0);
            $table->date('inicio_lectura')->nullable();
            $table->date('fin_lectura')->nullable();
            $table->string('resenha')->nullable()->default(0);
            $table->string('imagen')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libro');
    }
}
