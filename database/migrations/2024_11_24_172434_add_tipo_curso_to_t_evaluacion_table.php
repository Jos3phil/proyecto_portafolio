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
        Schema::table('TEvaluacion', function (Blueprint $table) {
            // Agregar la columna tipo_curso después de id_semestre
            $table->string('tipo_curso', 20)->after('id_semestre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('TEvaluacion', function (Blueprint $table) {
            // Eliminar la columna tipo_curso si se hace rollback
            $table->dropColumn('tipo_curso');
        });
    }
};
