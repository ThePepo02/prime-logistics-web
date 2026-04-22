<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TipusTransport extends Model
{
    protected $table = 'tipus_transports';


// Tipos de transporte: Marítimo, Terrestre, Aéreo
class TipusTransport extends Model
{
    protected $table = 'tipus_transports';
    public $timestamps = false;

    protected $fillable = ['tipus'];
}
