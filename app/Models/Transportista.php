<?php

namespace App\Models;

use App\Models\Ciutat;
use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportista extends Model
{
    protected $table = 'transportistes';
    public $timestamps = false;
    protected $fillable = ['id', 'nom', 'ciutat_id'];

    // Un transportista pertenece a una ciudad
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id', 'id');
    }

    // Un transportista puede tener muchas ofertas
    public function ofertes()
    {
        return $this->hasMany(Oferta::class, 'transportista_id');
    }
}
