<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $envios = Envio::paginate(10);
        return response()->json([
            'data' => $envios->map(fn ($e) => ['id' => $e->oferta_id, 'ref' => 'CL-' . $e->cliente_id, 'mode' => $e->metodo_transporte, 'route' => $e->ruta, 'weight' => $e->peso_kg . 'kg', 'date' => $e->fecha_pedido, 'etd' => '-', 'status' => $e->estado_envio]),
            'meta' => ['current_page' => $envios->currentPage(), 'last_page' => $envios->lastPage(), 'total' => $envios->total()],
        ]);
    }

    public function store(Request $request)
    {
        return response()->json(['id' => Envio::create($request->all())->id], 201);
    }
}
