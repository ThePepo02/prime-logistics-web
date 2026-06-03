<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo TrackingStep - Representa los pasos del tracking de una oferta
 * 
 * Cada oferta tiene múltiples pasos que representan su avance en el proceso
 * logístico: recolección, transporte, aduanal, entrega, etc.
 */
class TrackingStep extends Model
{
    protected $table = 'tracking_steps';
    public $timestamps = false;

    protected $fillable = [
        'oferta_id',
        'incoterm_id',
        'nom',
        'descripcio',
        'ordre',
        'estat',
        'estat_id',
        'ubicacio',
        'data_hora'
    ];

    protected $casts = [
        'data_hora' => 'datetime'
    ];

    /**
     * Un paso pertenece a una oferta
     */
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    /**
     * Un paso puede estar asociado a un incoterm
     */
    public function incoterm()
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id');
    }
}
