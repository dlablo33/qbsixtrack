<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMolecula2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('molecula2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bol')->nullable();
            $table->integer('order_number')->nullable();
            $table->integer('semana')->nullable();
            $table->date('fecha')->nullable();
            $table->string('linea', 50)->nullable();
            $table->text('no_pipa')->nullable();
            $table->string('cliente', 100)->nullable();
            $table->string('destino', 100)->nullable();
            $table->integer('transportista_id')->nullable();
            $table->integer('destino_id')->nullable();
            $table->string('status', 50)->default('pendiente');
            $table->string('cruce', 50)->default('rojo');
            $table->float('litros')->nullable();
            $table->timestamps();
            $table->float('precio')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_descarga')->nullable();
            $table->text('pedimento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('molecula2');
    }
}

