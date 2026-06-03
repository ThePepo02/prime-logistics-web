<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Oferta - Representa las ofertas de transporte logístico
 * 
 * Una oferta es una propuesta de transporte de mercancías que incluye
 * información del cliente, ruta, tipo de transporte, carga, etc.
 */
class Oferta extends Model
{
    protected $table = 'ofertes';
    public $timestamps = false;

    protected $fillable = [
        'tipus_transport_id',
        'tipus_fluxe_id',
        'tipus_carrega_id',
        'incoterm_id',
        'client_id',
        'comentaris',
        'agent_comercial_id',
        'transportista_id',
        'pes_brut',
        'volum',
        'tipus_validacio_id',
        'port_origen_id',
        'port_desti_id',
        'aeroport_origen_id',
        'aeroport_desti_id',
        'linia_transport_maritim_id',
        'estat_oferta_id',
        'operador_id',
        'data_creacio',
        'data_validessa_inicial',
        'data_validessa_final',
        'rao_rebuig',
        'tipus_contenidor_id',
        'estat_envio_id',
        'tracking_actual'
    ];

    protected $casts = [
        'data_creacio' => 'datetime',
        'data_validessa_inicial' => 'datetime',
        'data_validessa_final' => 'datetime',
    ];

    // Relaciones

    /**
     * La oferta pertenece a un cliente (usuario)
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * La oferta tiene un estado
     */
    public function estatOferta()
    {
        return $this->belongsTo(EstatOferta::class, 'estat_oferta_id');
    }

    /**
     * La oferta tiene un tipo de transporte
     */
    public function tipusTransport()
    {
        return $this->belongsTo(TipusTransport::class, 'tipus_transport_id');
    }

    /**
     * La oferta tiene un incoterm (término comercial)
     */
    public function incoterm()
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id');
    }

    /**
     * La oferta puede tener múltiples pasos de tracking
     */
    public function trackingSteps()
    {
        return $this->hasMany(TrackingStep::class, 'oferta_id');
    }

    /**
     * La oferta tiene un envío asociado
     */
    public function envio()
    {
        return $this->hasOne(Envio::class, 'oferta_id');
    }
}
