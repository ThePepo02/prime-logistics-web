<?php

namespace App\Models;

use App\Models\Ciutat;
use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aeroport extends Model
{
    protected $table = 'aeroports';
    public $timestamps = false;

    /**
     * Get the ciutats that owns the Aeroport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id', 'id');
    }

    /**
     * The ofertes that belong to the Aeroport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ofertes(): BelongsToMany
    {
        return $this->belongsToMany(Oferta::class, 'ofertes', 'aeroport_origen_id', 'aeroport_desti_id');
    }
}
