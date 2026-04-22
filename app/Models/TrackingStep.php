<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;

class TrackingStep extends Model
{
    protected $table = 'tracking_steps';

    protected $fillable = ['nom', 'ordre'];
=======
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
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
}
