<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Agregar campo de tracking actual a tabla ofertes
 * 
 * Esta migración agrega el campo tracking_actual a la tabla ofertes
 * para mantener el registro del paso actual en el que se encuentra cada oferta.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ofertes', function (Blueprint $table) {
            // Agregar columna para rastrear el paso actual
            $table->integer('tracking_actual')->default(1)->after('estat_oferta_id')
                  ->comment('Paso actual del tracking de la oferta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ofertes', function (Blueprint $table) {
            $table->dropColumn('tracking_actual');
        });
    }
};
