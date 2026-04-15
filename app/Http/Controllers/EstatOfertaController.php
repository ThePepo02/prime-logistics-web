<?php
namespace App\Http\Controllers;
use App\Models\EstatOferta;

class EstatOfertaController extends Controller
{
    // Devuelve todos los estados para el filtro del componente Vue
    public function index()
    {
        return response()->json(EstatOferta::all());
    }
}
