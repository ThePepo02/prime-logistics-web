<?php

namespace App\Models;

use App\Models\Ciutat;
use App\Models\Oferta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Port extends Model
{
    protected $table = 'ports';
    public $timestamps = false;

    /**
     * The ofertes that belong to the Port
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ofertes(): BelongsToMany
    {
        return $this->belongsToMany(Oferta::class, 'ofertes', 'port_origen_id', 'port_desti_id');
    }

    /**
     * Get the ciutats that owns the Port
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'ciutat_id', 'id');
    }
}
