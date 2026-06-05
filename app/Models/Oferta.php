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
        'tipus_transport_id',         // Tipo de transporte
        'tipus_fluxe_id',             // Flujo (importación/exportación)
        'tipus_carrega_id',           // Tipo de carga
        'incoterm_id',                // Condiciones de entrega
        'client_id',                  // Cliente asignado
        'comentaris',                 // Ruta o nota (ej: Barcelona → NY)
        'agent_comercial_id',         // Agente comercial
        'transportista_id',           // Transportista
        'pes_brut',                   // Peso en kg
        'volum',                      // Volumen en m³
        'tipus_validacio_id',         // Tipo de validación
        'port_origen_id',             // Puerto origen
        'port_desti_id',              // Puerto destino
        'aeroport_origen_id',         // Aeropuerto origen
        'aeroport_desti_id',          // Aeropuerto destino
        'linia_transport_maritim_id', // Línea marítima
        'estat_oferta_id',            // Estado de la oferta
        'operador_id',                // Operador que la gestiona
        'data_creacio',               // Fecha de creación
        'data_validessa_inicial',     // Inicio de validez
        'data_validessa_fina',        // Fin de validez
        'rao_rebuig',                 // Razón de rechazo
        'tipus_contenidor_id',        // Tipo de contenedor
        'estat_envio_id',             // Estado del envío
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
