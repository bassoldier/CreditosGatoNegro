<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('idVenta');
            $table->datetime('fechaHoraVenta');
            $table->string('numeroBoletaVenta');
            $table->integer('montoOriginalVenta');
            $table->integer('montoPostInteresVenta');
            $table->integer('montoPieVenta');
            $table->integer('numeroDeCuotasVenta');
            $table->float('factorInteresVenta');
            $table->integer('valorCuotaVenta');
            $table->unsignedBigInteger('idCliente');
            $table->foreign('idCliente')->references('idCliente')->on('clientes');
            $table->unsignedBigInteger('idVendedor');
            $table->foreign('idVendedor')->references('idVendedor')->on('vendedores');
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
        Schema::dropIfExists('ventas');
    }
}
