<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envios';
<<<<<<< HEAD

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
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'peso_kg' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
=======
    public $timestamps = false;
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
}
