<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complementos', function (Blueprint $table) {
            $table->id(); // Campo ID autoincremental
            $table->unsignedBigInteger('pago_id')->nullable(); // Relación con la tabla de pagos
            $table->string('banco_proveniente')->nullable(); // Banco proveniente
            $table->string('numero_cuenta')->nullable(); // Número de cuenta
            $table->string('serial_baunche')->nullable(); // Serial de baunche
            $table->timestamps(); // Campos created_at y updated_at

            // Definir la clave foránea, si es necesario
            $table->foreign('pago_id')->references('id')->on('pagos')->onDelete('cascade'); // Asegúrate de que la tabla 'pagos' exista
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complementos', function (Blueprint $table) {
            $table->dropForeign(['pago_id']); // Eliminar la clave foránea
        });
        Schema::dropIfExists('complementos'); // Eliminar la tabla
    }
}
