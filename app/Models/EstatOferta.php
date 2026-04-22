<?php
<<<<<<< HEAD

=======
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class EstatOferta extends Model
{
    protected $table = 'estats_ofertes';

=======
// Estados posibles de una oferta: Enviada, Acceptada, Rebutjada...
class EstatOferta extends Model
{
    protected $table = 'estats_ofertes';
    public $timestamps = false;
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
    protected $fillable = ['estat'];
}
