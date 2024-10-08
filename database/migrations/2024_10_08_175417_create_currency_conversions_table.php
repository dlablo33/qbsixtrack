<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyConversionsTable extends Migration
{
    public function up()
    {
        Schema::create('currency_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_cuenta_id')->constrained(); // Relación con la cuenta de la empresa
            $table->decimal('amount', 10, 2);
            $table->string('from_currency');
            $table->string('to_currency');
            $table->decimal('exchange_rate', 10, 2);
            $table->decimal('converted_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_conversions');
    }
}

