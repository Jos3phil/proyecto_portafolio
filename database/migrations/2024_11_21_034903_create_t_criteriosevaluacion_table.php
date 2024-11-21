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
        Schema::create('TCriteriosEvaluacion', function (Blueprint $table) {
            $table->increments('id_criterio');
            $table->string('descripcion_criterio');
            $table->unsignedInteger('id_seccion');
            $table->boolean('obligatoriedad');
            $table->integer('peso');
            $table->enum('tipo_curso', ['TEORIA', 'PRACTICA', 'AMBOS'])->default('AMBOS'); // Nuevo campo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TCriteriosEvaluacion');
    }
};
