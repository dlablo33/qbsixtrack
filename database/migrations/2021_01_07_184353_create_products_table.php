<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('ItemId')->unique();
            $table->string('Name');
            $table->text('Description')->nullable();
            $table->boolean('Active');
            $table->string('FullyQualifiedName');
            $table->boolean('Taxable');
            $table->decimal('UnitPrice', 10, 2);
            $table->string('Type');
            $table->json('IncomeAccountRef');
            $table->decimal('PurchaseCost', 10, 2);
            $table->boolean('TrackQtyOnHand');
            $table->string('domain');
            $table->boolean('sparse');
            $table->integer('SyncToken');
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
        Schema::dropIfExists('products');
    }
}
