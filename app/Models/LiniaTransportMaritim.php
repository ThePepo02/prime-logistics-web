<?php

namespace App\Models;

use App\Models\Ciutat;
use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiniaTransportMaritim extends Model
{
    protected $table = 'linies_transport_maritim';
    public $timestamps = false;

    /**
     * Get the ofertes that owns the LiniaTransportMaritim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ofertes(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'linia_transport_maritim_id', 'id');
    }

    /**
     * Get the ciutats that owns the LiniaTransportMaritim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id', 'id');
    }
}
