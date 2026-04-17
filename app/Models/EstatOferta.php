<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Estados posibles de una oferta: Enviada, Acceptada, Rebutjada...
class EstatOferta extends Model
{
    protected $table = 'estats_ofertes';
    public $timestamps = false;
    protected $fillable = ['estat'];
}
