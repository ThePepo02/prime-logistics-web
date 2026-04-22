<?php
namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function index()
    {
        $ofertes = Oferta::with(['client', 'estatOferta', 'tipusTransport']) // Carga relaciones en una sola query
            ->orderBy('id', 'desc') // Más recientes primero
            ->paginate(8);          // 8 registros por página

        return response()->json($ofertes); // Devuelve JSON para Vue
    }
}
