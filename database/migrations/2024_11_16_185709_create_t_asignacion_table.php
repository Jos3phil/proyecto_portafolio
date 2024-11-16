<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('TAsignacion', function (Blueprint $table) {
            $table->string('id_asignacion', 255)->primary();
            $table->string('id_supervisor', 255);
            $table->string('id_docente', 255);
            $table->string('id_semestre', 255);
            $table->timestamps();

            $table->foreign('id_supervisor')->references('id_usuario')->on('TUsuario')->onDelete('cascade');
            $table->foreign('id_docente')->references('id_usuario')->on('TUsuario')->onDelete('cascade');
            $table->foreign('id_semestre')->references('id_semestre')->on('TSemestre')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TAsignacion');
    }
};
