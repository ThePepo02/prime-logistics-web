<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    // Apunta a la tabla usuaris porque los clientes son usuarios con rol_id = 3
    protected $table = 'usuaris';

    public $timestamps = false;

    protected $fillable = [
        'correu',
        'nom',
        'cognoms',
        'empresa',
        'rol_id',
    ];

    // Oculta la contraseña para que nunca salga en la API
    protected $hidden = ['contrasenya'];

    // Scope que filtra automáticamente solo los usuarios con rol_id = 3
    public function scopeClientes($query)
    {
        return $query->where('rol_id', 3);
    }
}
