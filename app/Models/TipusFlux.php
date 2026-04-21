<?php

namespace App\Models;

use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipusFlux extends Model
{
    protected $table = 'tipus_fluxes';
    public $timestamps = false;

    /**
     * Get the ofertes that owns the TipusFlux
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'tipus_fluxe_id', 'id');
    }
}
