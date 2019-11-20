<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdicionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adicionales', function (Blueprint $table) {
            $table->bigIncrements('idAdicional');
            $table->string('rutAdicional')->unique();
            $table->string('nombreAdicional');
            $table->string('apellidoPatAdicional');
            $table->string('apellidoMatAdicional');
            $table->string('telefonoAdicional');
            $table->string('correoAdicional')->nullable();
            $table->string('direccionAdicional');
            $table->unsignedBigInteger('idCliente');
            $table->foreign('idCliente')->references('idCliente')->on('clientes');
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
        Schema::dropIfExists('adicionales');
    }
}
