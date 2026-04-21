<?php

namespace App\Models;

use App\Models\Incoterm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipusIncoterms extends Model
{
    protected $table = 'tipus_incoterms';
    public $timestamps = false;

    /**
     * Get the incoterms that owns the TipusIncoterms
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function incoterms(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class, 'tipus_incoterms_id', 'id');
    }
}
