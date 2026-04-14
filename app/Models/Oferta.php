<?php
namespace App\Models;

use App\Models\Envio;
use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table   = 'ofertes';
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
    ];

    public function client()
    {
        return $this->belongsTo(Usuarios::class, 'client_id');
    }

    public function envio()
    {
        return $this->hasOne(Envio::class, 'oferta_id');
    }
}
