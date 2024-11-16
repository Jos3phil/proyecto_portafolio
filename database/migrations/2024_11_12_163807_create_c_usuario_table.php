<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('TUsuario', function (Blueprint $table) {
            $table->string('id_usuario', 255)->primary();
            $table->string('Nombre', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cUsuario');
    }
}