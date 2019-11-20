<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAumentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aumenta', function (Blueprint $table) {
            $table->bigIncrements('idAumenta');
            $table->unsignedBigInteger('idDeudaMensual');
            $table->foreign('idDeudaMensual')->references('idDeudaMensual')->on('deuda_mensual');
            $table->unsignedBigInteger('idSaldo');
            $table->foreign('idSaldo')->references('idSaldo')->on('saldos');
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
        Schema::dropIfExists('aumenta');
    }
}
