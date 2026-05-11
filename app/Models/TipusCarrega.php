<?php

namespace App\Models;

use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipusCarrega extends Model
{
    protected $table = 'tipus_carrega';
    public $timestamps = false;

    /**
     * Get the ofertes that owns the TipusCarrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'tipus_carrega_id', 'id');
    }
}
