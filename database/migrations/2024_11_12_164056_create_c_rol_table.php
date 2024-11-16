<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRolTable extends Migration
{
    public function up()
    {
        Schema::create('TRol', function (Blueprint $table) {
            $table->string('id_rol', 255)->primary();
            $table->string('tipo_rol', 255);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('TRol');
    }
}

