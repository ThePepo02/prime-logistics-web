<?php

namespace App\Models;

use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipusValidacio extends Model
{
    protected $table = 'tipus_validacions';
    public $timestamps = false;

    /**
     * Get the ofertes that owns the TipusValidacio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'tipus_validacio_id', 'id');
    }
}
