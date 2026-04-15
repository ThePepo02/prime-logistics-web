<?php

namespace App\Models;

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
        'rol_id',
    ];

    protected $hidden = ['contrasenya'];

    public function getAuthPassword()
    {
        return $this->contrasenya;
    }
}
