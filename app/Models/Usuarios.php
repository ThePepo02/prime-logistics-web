<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    //
    protected $table = 'usuaris';

    public $timestamps = false;

    protected $fillable = [
        'correu',
        'contrasenya',
        'nom',
        'cognoms',
        'empresa',
        'rol_id',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
