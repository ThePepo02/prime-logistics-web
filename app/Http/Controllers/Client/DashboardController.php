<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Envio;

class DashboardController extends Controller
{
    public function index()
    {
        $envios = Envio::all();
        return response()->json([
            'kpis' => [
                'active' => $envios->where('estado_envio', 'En tránsito')->count(),
                'delivered' => $envios->where('estado_envio', 'Entregado hoy')->count(),
                'delayed' => 0,
                'incidents' => $envios->where('estado_envio', 'En preparación')->count(),
            ],
            'recentOrders' => $envios->map(fn ($e) => ['id' => $e->oferta_id, 'mode' => $e->metodo_transporte, 'route' => $e->ruta, 'date' => $e->fecha_pedido, 'status' => $e->estado_envio])->take(5)
        ]);
    }
}
