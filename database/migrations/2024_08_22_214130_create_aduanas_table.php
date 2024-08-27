<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAduanasTable extends Migration
{
    public function up()
    {
        Schema::create('aduanas', function (Blueprint $table) {
            $table->id();
            $table->string('bol_number'); // Número del BoL
            $table->decimal('precio', 10, 2); // Precio asignado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aduanas');
    }
}

