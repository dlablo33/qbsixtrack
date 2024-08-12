<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaCuentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_cuenta', function (Blueprint $table) {
            $table->id();
            $table->string('banco');
            $table->decimal('ingreso_mxn', 15, 2)->default(0);
            $table->decimal('ingreso_usd', 15, 2)->default(0);
            $table->decimal('comision_mxn', 15, 2)->default(0);
            $table->decimal('comision_usd', 15, 2)->default(0);
            $table->decimal('saldo_final_mxn', 15, 2)->default(0);
            $table->decimal('saldo_final_usd', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresa_cuenta');
    }
}

