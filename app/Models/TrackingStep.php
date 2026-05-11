<?php

namespace App\Models;

use App\Models\Incoterm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingStep extends Model
{
    protected $table   = 'tracking_steps';
    public $timestamps = false;

    protected $fillable = [
        'oferta_id',
        'estat',
        'descripcio',
        'ubicacio',
        'data_hora',
    ];

    public function incoterms(): BelongsTo
    {
        return $this->belongsTo(Incoterm::class, 'tracking_steps_id', 'id');
    }
}
