<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incoterm extends Model
{
    protected $table = 'incoterms';
    public $timestamps = false;
    protected $fillable = ['id', 'tipus_inconterm_id', 'tracking_steps_id'];
}
