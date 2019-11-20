<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeudaMensualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deuda_mensual', function (Blueprint $table) {
            $table->bigIncrements('idDeudaMensual');
            $table->integer('montoDeudaMensual');
            $table->date('fechaVencimientoDeudaMensual');
            $table->date('fechaActualizacionDeudaMensual')->nullable();
            $table->date('mesCorrespondienteDeudaMensual')->nullable();
            $table->integer('montoMoraDeudaMensual');
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
        Schema::dropIfExists('deuda_mensual');
    }
}
