<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCUsuarioRolesTable extends Migration
{
    public function up()
    {
        Schema::create('TUsuarioRoles', function (Blueprint $table) {
            $table->string('id_usuario_rol', 255)->primary();
            $table->string('id_usuario', 255);
            $table->string('id_rol', 255);
            $table->foreign('id_usuario')->references('id_usuario')->on('TUsuario')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_rol')->references('id_rol')->on('TRol')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('TUsuarioRoles');
    }
}
