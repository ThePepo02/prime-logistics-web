<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingStep extends Model
{
    protected $table = 'tracking_steps';

    protected $fillable = ['nom', 'ordre'];
}
