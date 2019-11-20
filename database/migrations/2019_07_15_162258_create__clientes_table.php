<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Clientes', function (Blueprint $table) {
            $table->bigIncrements('idCliente');
            $table->string('rutCliente')->unique();
            $table->string('nombreCliente');
            $table->string('apellidoPatCliente');
            $table->string('apellidoMatCliente');
            $table->string('telefonoCliente');
            $table->string('correoCliente')->nullable();
            $table->string('direccionCliente');
            $table->string('rutRecomendadoCliente')->nullable();
            $table->date('fechaPagoCliente');
            $table->date('fechaFacturacionCliente');
            $table->integer('deudaTotalCliente');
            $table->boolean('morosoCliente');
            $table->boolean('bloqueoCliente');
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
        Schema::dropIfExists('Clientes');
    }
}
