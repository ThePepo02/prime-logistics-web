<?php

namespace App\Models;

use App\Models\Ciutat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportista extends Model
{
    protected $table = 'transportistes';
    public $timestamps = false;

    /**
     * Get the ciutats that owns the Transportista
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id', 'id');
    }

    /**
     * Get the ofertes that owns the Transportista
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'transportista_id', 'id');
    }
}
