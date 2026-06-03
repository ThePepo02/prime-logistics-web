<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Crear tabla de pasos de tracking
 * 
 * Esta migración crea la tabla que almacena los pasos del tracking
 * de cada oferta logística (recolección, transporte, aduanal, entrega, etc.)
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
        Schema::create('tracking_steps', function (Blueprint $table) {
            $table->id();
            
            // Referencia a la oferta
            $table->unsignedBigInteger('oferta_id')->comment('ID de la oferta');
            $table->foreign('oferta_id')->references('id')->on('ofertes')->onDelete('cascade');
            
            // Referencia al incoterm (opcional)
            $table->unsignedBigInteger('incoterm_id')->nullable()->comment('ID del incoterm');
            $table->foreign('incoterm_id')->references('id')->on('incoterms')->onDelete('set null');
            
            // Nombre del paso (Recolección, Transporte, etc.)
            $table->string('nom')->nullable()->comment('Nombre del paso de tracking');
            
            // Descripción del paso
            $table->longText('descripcio')->nullable()->comment('Descripción detallada del paso');
            
            // Orden en el que se ejecutan los pasos (1, 2, 3, ...)
            $table->integer('ordre')->default(1)->comment('Orden del paso en el proceso');
            
            // Estado del paso (pendiente, en progreso, completado)
            $table->string('estat')->nullable()->comment('Estado del paso');
            
            // Foreign key a tabla de estados (si existe)
            $table->unsignedBigInteger('estat_id')->nullable()->comment('ID del estado del paso');
            
            // Ubicación del envío en este paso
            $table->string('ubicacio')->nullable()->comment('Ubicación del envío en este paso');
            
            // Fecha y hora del paso
            $table->dateTime('data_hora')->nullable()->comment('Fecha y hora del paso');
            
            // Índices para búsquedas rápidas
            $table->index('oferta_id');
            $table->index('ordre');
            $table->index('estat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_steps');
    }
};
