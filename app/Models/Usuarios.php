<?php

namespace App\Models;

use App\Models\Rol;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuaris';
    public $timestamps = false;

    protected $fillable = [
        'correu',
        'contrasenya',
        'nom',
        'cognoms',
        'empresa',
        'cif',
        'telefon',
        'actiu',
        'rol_id',
    ];

    protected $hidden = [
        'contrasenya',
    ];

    // Laravel busca el campo "password" por defecto
    // como nuestra columna se llama "contrasenya" se lo indicamos aquí
    public function getAuthPassword()
    {
        return $this->contrasenya;
    }

    // Un usuario pertenece a un rol (Cliente, Operador, Admin)
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Un usuario puede tener muchas ofertas como cliente
    public function ofertes()
    {
        return $this->hasMany(\App\Models\Oferta::class, 'client_id');
    }
}
