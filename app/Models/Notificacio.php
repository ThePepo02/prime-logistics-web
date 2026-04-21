<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacio extends Model
{
    protected $table      = 'notificacions';
    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable = [
        'tipus',
        'titol',
        'missatge',
        'usuari_id',
        'entitat_tipus',
        'entitat_id',
        'llegida',
        'data_creacio',
    ];

    protected $casts = [
        'llegida'      => 'boolean',
        'data_creacio' => 'datetime',
    ];
}
