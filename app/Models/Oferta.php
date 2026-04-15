<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertes';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'estat_oferta_id',
        'tipus_transport_id',
        'comentaris',
        'data_creacio',
    ];

    protected $casts = [
        'data_creacio' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function estatOferta()
    {
        return $this->belongsTo(EstatOferta::class, 'estat_oferta_id');
    }

    public function tipusTransport()
    {
        return $this->belongsTo(TipusTransport::class, 'tipus_transport_id');
    }
}
