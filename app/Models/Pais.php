<?php

namespace App\Models;

use App\Models\Ciutat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pais extends Model
{
    protected $table = 'paisos';
    public $timestamps = false;
    protected $fillable = ['id', 'nom'];
    /**
     * Get the ciutats that owns the Pais
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciutats(): BelongsTo
    {
        return $this->belongsTo(Ciutat::class, 'pais_id', 'id');
    }
}
