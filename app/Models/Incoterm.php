<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Incoterm - Representa los tipos de incoterm
 * 
 * Los incoterms (International Commercial Terms) son los términos 
 * estandarizados utilizados en el comercio internacional para indicar
 * las responsabilidades de comprador y vendedor en la entrega.
 * 
 * Ejemplos: CIF, FOB, DDP, etc.
 */
class Incoterm extends Model
{
    protected $table = 'incoterms';
    public $timestamps = false;

    protected $fillable = [
        'codi',
        'nom',
        'descripcio'
    ];

    /**
     * Los tipos de incoterm pueden tener pasos de tracking
     */
    public function trackingSteps()
    {
        return $this->hasMany(TrackingStep::class, 'incoterm_id');
    }
}
