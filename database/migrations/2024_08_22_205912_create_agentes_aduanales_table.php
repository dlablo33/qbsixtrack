<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentesAduanalesTable extends Migration
{
    public function up()
    {
        Schema::create('agentes_aduanales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique(); // Código único para el agente aduanal
            $table->string('rfc')->unique();    // RFC del agente
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();               // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('agentes_aduanales');
    }
}
