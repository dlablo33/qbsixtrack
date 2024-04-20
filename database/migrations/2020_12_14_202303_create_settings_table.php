<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('auth_mode')->default('oauth2');
            $table->string('ClientID')->nullable();
            $table->string('ClientSecret')->nullable();
            $table->string('RedirectURI')->nullable();
            $table->string('scope')->default('com.intuit.quickbooks.accounting openid profile email phone address');
            $table->string('baseUrl')->default('development');
            $table->string('QBORealmID')->nullable();
            $table->string('others')->nullable();
            $table->string('status')->default(1);
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
        Schema::dropIfExists('settings');
    }
}
