<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciutat extends Model
{
    protected $table = 'ciutats';
    public $timestamps = false;
    protected $fillable = ['id', 'nom', 'pais_id'];

    // Una ciudad puede tener muchos puertos
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class, 'ciutat_id');
    }

    // Una ciudad puede tener muchos transportistas
    public function transportistes(): HasMany
    {
        return $this->hasMany(Transportista::class, 'ciutat_id');
    }
}
