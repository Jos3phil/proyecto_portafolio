<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration class for creating the 'TUsuario' table.
 * 
 * This class defines the schema for the 'TUsuario' table, including columns for
 * 'id_usuario', 'Nombre', 'email', and 'password'. It also includes timestamps
 * for created_at and updated_at.
 * 
 * Methods:
 * - up(): Creates the 'TUsuario' table with the specified columns and constraints.
 * - down(): Drops the 'cUsuario' table if it exists.
 */
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