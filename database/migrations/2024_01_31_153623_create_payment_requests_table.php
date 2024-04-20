<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained(); // Assuming a foreign key relationship with the Customer model
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_requests');
    }
}
