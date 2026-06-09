<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clientes;

class Envio extends Model
{
    protected $table = 'envios';

    protected $fillable = [
        'origen',
        'destino',
        'estado_envio',
        'oferta_id',
        'contenido_envio',
        'metodo_transporte',
        'tipo_divisa',
        'fecha_pedido',
        'cliente',
        'ruta',
        'peso_kg',
        'incoterm',
        'urgencia',
        'compania',
        'cliente_id',
        'tracking_actual',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'peso_kg' => 'decimal:2',
    ];


    public function client()
    {
    return $this->belongsTo(Clientes::class, 'cliente_id');
    }
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    public $timestamps = false;
}