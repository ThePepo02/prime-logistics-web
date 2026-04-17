<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tipos de transporte: Marítimo, Terrestre, Aéreo
class TipusTransport extends Model
{
    protected $table = 'tipus_transports';
    public $timestamps = false;
    protected $fillable = ['tipus'];
}
