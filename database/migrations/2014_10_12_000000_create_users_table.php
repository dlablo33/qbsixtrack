<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id como clave primaria
            $table->string('name'); // nombre del usuario
            $table->string('email')->unique(); // email único
            $table->timestamp('email_verified_at')->nullable(); // verificación de email
            $table->string('password'); // contraseña
            $table->integer('tipo_usuario')->default(3); // tipo de usuario, con valor predeterminado 3
            $table->rememberToken(); // token de recuerdo
            $table->timestamps(); // timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
