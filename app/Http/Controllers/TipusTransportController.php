<?php
namespace App\Http\Controllers;
use App\Models\TipusTransport;

class TipusTransportController extends Controller
{
    // Devuelve todos los transportes para el filtro del componente Vue
    public function index()
    {
        return response()->json(TipusTransport::all());
    }
}
