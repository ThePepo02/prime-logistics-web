<?php
namespace App\Models;

use App\Models\Envio;
use App\Models\Usuarios;
use App\Models\EstatOferta;
use App\Models\TipusTransport;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertes'; // Tabla en SQL Server
    public $timestamps = false;   // Sin created_at / updated_at

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
        'data_validessa_final',       // Fin de validez
        'rao_rebuig',                 // Razón de rechazo
        'tipus_contenidor_id',        // Tipo de contenedor
        'estat_envio_id',             // Estado del envío
    ];

    // Oferta → pertenece a un Usuario (cliente)
    public function client()
    {
        return $this->belongsTo(Usuarios::class, 'client_id');
    }

    // Oferta → tiene un Envio asociado
    public function envio()
    {
        return $this->hasOne(Envio::class, 'oferta_id');
    }

    // Oferta → pertenece a un Estado (Enviada, Aceptada...)
    public function estatOferta()
    {
        return $this->belongsTo(EstatOferta::class, 'estat_oferta_id');
    }

    // Oferta → pertenece a un Tipo de Transporte (Marítimo, Aéreo...)
    public function tipusTransport()
    {
        return $this->belongsTo(TipusTransport::class, 'tipus_transport_id');
    }
}
