<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraspasosTable extends Migration
{
    public function up()
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_origen')->constrained('empresa_cuenta')->onDelete('cascade');
            $table->foreignId('banco_destino')->constrained('empresa_cuenta')->onDelete('cascade');
            $table->decimal('cantidad', 15, 2);
            $table->enum('moneda', ['MXN', 'USD']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traspasos');
    }
}
