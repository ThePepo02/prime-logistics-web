<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Oferta;
use Illuminate\Http\Request;

class ClientDataController extends Controller
{
    public function dashboard()
    {
        $envios = Envio::all();
        return response()->json([
            'kpis' => [
                'active' => $envios->where('estado_envio', 'En tránsito')->count(),
                'delivered' => $envios->where('estado_envio', 'Entregado hoy')->count(),
                'delayed' => 0,
                'incidents' => $envios->where('estado_envio', 'En preparación')->count(),
            ],
            'recentOrders' => $envios->map(fn ($e) => ['id' => $e->oferta_id, 'mode' => $e->metodo_transporte, 'route' => $e->ruta, 'date' => $e->fecha_pedido, 'status' => $e->estado_envio])->take(5),
            'chartData' => [],
        ]);
    }

    public function orders(Request $request)
    {
        $envios = Envio::paginate(8);
        return response()->json([
            'data' => $envios->map(fn ($e) => ['id' => $e->oferta_id, 'ref' => 'CL-' . $e->cliente_id, 'mode' => $e->metodo_transporte, 'route' => $e->ruta, 'weight' => $e->peso_kg . 'kg', 'date' => $e->fecha_pedido, 'etd' => '-', 'status' => $e->estado_envio]),
            'meta' => ['current_page' => $envios->currentPage(), 'last_page' => $envios->lastPage(), 'total' => $envios->total()],
        ]);
    }

    public function createOrder(Request $request)
    {
        return response()->json(['id' => Envio::create($request->all())->id], 201);
    }

    public function tracking(Request $request)
    {
        $envio = Envio::where('oferta_id', $request->code)->orWhere('id', substr($request->code, 3))->first();
        return response()->json($envio ? ['code' => $envio->oferta_id, 'status' => $envio->estado_envio] : ['error' => 'No encontrado'], $envio ? 200 : 404);
    }

    public function notifications()
    {
        $offers = Oferta::latest('id')->limit(12)->get();
        $notices = $offers->map(fn ($o, $i) => [
            'id' => $o->id,
            'title' => 'Oferta #' . $o->id,
            'message' => $o->comentaris ?: 'Nueva oferta',
            'code' => 'OC-' . $o->id,
            'type' => 'Pendiente',
            'typeClass' => 'sent',
            'time' => $o->data_creacio?->format('Y-m-d') ?? '-',
            'featured' => $i === 0,
            'primary' => 'Ver',
            'secondary' => $i === 0 ? 'Rechazar' : null,
        ])->toArray();

        return response()->json([
            'notices' => $notices,
            'priority' => $notices[0] ?? null,
            'stats' => ['unread' => min(3, count($notices)), 'total' => count($notices)],
        ]);
    }

    public function acceptOffer(Request $request)
    {
        Oferta::find($request->offer_id)?->update(['estat_oferta_id' => 2]);
        return response()->json(['ok' => true]);
    }

    public function rejectOffer(Request $request)
    {
        Oferta::find($request->offer_id)?->update(['estat_oferta_id' => 3]);
        return response()->json(['ok' => true]);
    }
}
