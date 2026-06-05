<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Envio;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $envios = Envio::all();
            return response()->json([
                'kpis' => [
                    'active' => $envios->where('estado_envio', 'En tránsito')->count(),
                    'delivered' => $envios->where('estado_envio', 'Entregado hoy')->count(),
                    'delayed' => 0,
                    'incidents' => $envios->where('estado_envio', 'En preparación')->count(),
                ],
                'recentOrders' => $envios->map(fn ($e) => [
                    'id' => $e->oferta_id ?? 'N/A',
                    'mode' => $e->metodo_transporte ?? 'N/A',
                    'route' => $e->ruta ?? 'N/A',
                    'date' => $e->fecha_pedido ?? now()->toDateString(),
                    'status' => $e->estado_envio ?? 'PENDIENTE'
                ])->take(5)
            ]);
        } catch (\Exception $e) {
            // Return mock data if there's an error
            return response()->json([
                'kpis' => [
                    'active' => 0,
                    'delivered' => 0,
                    'delayed' => 0,
                    'incidents' => 0,
                ],
                'recentOrders' => []
            ]);
        }
    }
}
