<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->bigIncrements('idCuota');
            $table->integer('montoCuota');
            $table->date('mesCorrespondenciaCuota');
            $table->unsignedBigInteger('idVenta');
            $table->foreign('idVenta')->references('idVenta')->on('ventas');
            $table->unsignedBigInteger('idDeudaMensual');
            $table->foreign('idDeudaMensual')->references('idDeudaMensual')->on('deuda_mensual');
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
        Schema::dropIfExists('cuotas');
    }
}
