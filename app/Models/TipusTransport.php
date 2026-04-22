<?php
<<<<<<< HEAD

=======
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class TipusTransport extends Model
{
    protected $table = 'tipus_transports';

=======
// Tipos de transporte: Marítimo, Terrestre, Aéreo
class TipusTransport extends Model
{
    protected $table = 'tipus_transports';
    public $timestamps = false;
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
    protected $fillable = ['tipus'];
}
