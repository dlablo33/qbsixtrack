<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('clasificacion');
            $table->string('beneficiario');
            $table->string('descripcion')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->unsignedBigInteger('banco_id');
            $table->string('moneda');
            $table->timestamps();
    
            $table->foreign('banco_id')->references('id')->on('bancos');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos');
    }
}
