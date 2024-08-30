<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_aduana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bol_id')->constrained('aduanas'); // Relaciona con BoLs
            $table->decimal('cantidad', 8, 2);
            $table->date('fecha');
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
        Schema::dropIfExists('pagos');
    }
}
