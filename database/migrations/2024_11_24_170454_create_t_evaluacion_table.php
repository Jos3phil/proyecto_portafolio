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
        Schema::create('TEvaluacion', function (Blueprint $table) {
            $table->string('id_evaluacion', 255)->primary();
            $table->string('id_asignacion', 255);
            $table->string('id_semestre', 255);
            $table->timestamp('fecha_evaluacion')->useCurrent();
            $table->timestamps();

            $table->foreign('id_asignacion')->references('id_asignacion')->on('TAsignacion')->onDelete('cascade');
            $table->foreign('id_semestre')->references('id_semestre')->on('TSemestre')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TEvaluacion');
    }
};
