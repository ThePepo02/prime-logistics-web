<?php

namespace App\Models;

use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipusContenidors extends Model
{
    protected $table = 'tipus_contenidors';
    public $timestamps = false;

    /**
     * Get the user that owns the TipusContenidors
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'tipus_contenidor_id', 'id');
    }
}
