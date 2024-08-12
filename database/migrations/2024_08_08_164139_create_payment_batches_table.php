<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('payment_batch_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->string('bol_number');
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('payment_batches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_batch_items');
        Schema::dropIfExists('payment_batches');
    }
}

