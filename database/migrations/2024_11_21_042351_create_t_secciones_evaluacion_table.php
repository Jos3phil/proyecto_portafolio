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
        Schema::create('TSeccionesEvaluacion', function (Blueprint $table) {
            $table->increments('id_seccion');
            $table->string('nombre_seccion');
            $table->string('descripcion_seccion')->nullable();
            $table->boolean('obligatoriedad')->default(true); // Campo para indicar si la sección es obligatoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TSeccionesEvaluacion');
    }
};
