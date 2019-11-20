<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos', function (Blueprint $table) {
            $table->bigIncrements('idSaldo');
            $table->integer('montoSaldo');
            $table->datetime('fechaSaldo');
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
        Schema::dropIfExists('saldos');
    }
}
