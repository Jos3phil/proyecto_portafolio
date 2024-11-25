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
        Schema::create('TDetalleEvaluacion', function (Blueprint $table) {
            $table->increments('id_detalle');
            $table->string('id_evaluacion', 255);
            $table->unsignedInteger('id_criterio');
            $table->boolean('cumple');
            $table->string('comentario')->nullable();
            $table->timestamps();

            $table->foreign('id_evaluacion')->references('id_evaluacion')->on('TEvaluacion')->onDelete('cascade');
            $table->foreign('id_criterio')->references('id_criterio')->on('TCriteriosEvaluacion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TDetalleEvaluacion');
    }
};
