<?php

namespace App\Models;

use App\Models\Incoterm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;  // ← cambiar el import
class TipusIncoterm extends Model
{
    protected $table = 'tipus_incoterms';
    public $timestamps = false;
    protected $fillable = ['id', 'codi', 'nom'];

    /**
     * Get the incoterms that owns the TipusIncoterms
     *
     */
    public function incoterms(): HasMany
    {
        return $this->hasMany(Incoterm::class, 'tipus_inconterm_id');
    }
}
