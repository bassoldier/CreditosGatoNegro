<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecibeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibe', function (Blueprint $table) {
            $table->bigIncrements('idRecibe');
            $table->unsignedBigInteger('idVenta');
            $table->foreign('idVenta')->references('idVenta')->on('ventas');
            $table->unsignedBigInteger('idAdicional');
            $table->foreign('idAdicional')->references('idAdicional')->on('adicionales');
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
        Schema::dropIfExists('recibe');
    }
}
