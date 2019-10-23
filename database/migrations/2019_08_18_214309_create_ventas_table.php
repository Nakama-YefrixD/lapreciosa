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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tipoComprobante_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tipoMoneda_id');
            $table->integer('numero');
            $table->date('fecha');
            $table->date('fechaVencimiento');
            $table->string('Observaciones');
            $table->string('descuento')->nullable();
            $table->integer('igv');
            $table->string('impuestos');
            $table->string('subtotal');
            $table->string('total');
            $table->boolean('estadoEmail');
            $table->boolean('estadoSunat');
            $table->string('xml')->nullable();
            $table->string('cdr')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();

            $table->foreign('tipoComprobante_id')->references('id')->on('tiposComprobante');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tipoMoneda_id')->references('id')->on('tiposMoneda');
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
