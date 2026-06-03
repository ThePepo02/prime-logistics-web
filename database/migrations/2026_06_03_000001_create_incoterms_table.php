<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Crear tabla de tipos de incoterm
 * 
 * Esta migración crea la tabla que almacena los diferentes tipos de incoterm
 * disponibles en el sistema (CIF, FOB, DDP, etc.)
 * 
 * Para ejecutar: php artisan migrate
 * Para revertir: php artisan migrate:rollback
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incoterms', function (Blueprint $table) {
            $table->id();
            
            // Código único del incoterm (CIF, FOB, DDP, etc.)
            $table->string('codi', 10)->unique()->comment('Código del incoterm (ej: CIF, FOB)');
            
            // Nombre completo del incoterm
            $table->string('nom')->comment('Nombre completo del incoterm');
            
            // Descripción detallada
            $table->longText('descripcio')->nullable()->comment('Descripción detallada del incoterm');
            
            // Sin timestamps, así que no incluimos created_at/updated_at
            // Si quieres agregarlos, descomenta:
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoterms');
    }
};
