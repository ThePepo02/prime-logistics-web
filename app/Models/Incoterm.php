<?php

namespace App\Models;

use App\Models\TipusIncoterm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incoterm extends Model
{
    protected $table = 'incoterms';
    public $timestamps = false;

    /**
     * Get the tipus_incoterms that owns the Incoterm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipus_incoterms(): BelongsTo
    {
        return $this->belongsTo(TipusIncoterm::class, 'foreign_key', 'other_key');
    }
}
