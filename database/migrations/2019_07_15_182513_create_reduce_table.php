<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReduceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reduce', function (Blueprint $table) {
            $table->bigIncrements('idReduce');
            $table->integer('montoReduccion');
            $table->unsignedBigInteger('idDeudaMensual');
            $table->foreign('idDeudaMensual')->references('idDeudaMensual')->on('deuda_mensual');
            $table->unsignedBigInteger('idAbono');
            $table->foreign('idAbono')->references('idAbono')->on('abonos');
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
        Schema::dropIfExists('reduce');
    }
}
