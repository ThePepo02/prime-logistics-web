<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperacionesController extends Controller
{
    // KPI cards: total en tránsito, aéreo y marítimo
    public function stats()
    {
        $total = DB::table('envios')->count();

        $aerea = DB::table('envios')
            ->where('metodo_transporte', 'Aéreo')
            ->count();

        $maritima = DB::table('envios')
            ->where('metodo_transporte', 'Marítimo')
            ->count();

        return response()->json([
            'total'        => $total,
            'aerea'        => $aerea,
            'maritima'     => $maritima,
            'pct_aerea'    => $total > 0 ? round(($aerea / $total) * 100) : 0,
            'pct_maritima' => $total > 0 ? round(($maritima / $total) * 100) : 0,
        ]);
    }

    // Barras de distribución por modo de transporte
    public function distribucio()
    {
        $total = DB::table('envios')->count();

        $modos = DB::table('envios')
            ->select('metodo_transporte', DB::raw('COUNT(*) as total'))
            ->groupBy('metodo_transporte')
            ->get();

        return response()->json($modos->map(fn($m) => [
            'modo'  => $m->metodo_transporte,
            'total' => $m->total,
            'pct'   => $total > 0 ? round(($m->total / $total) * 100) : 0,
        ]));
    }

    // Tabla con búsqueda, filtro por modo y paginación
    public function operacions(Request $request)
    {
        $query = DB::table('envios');

        // Filtro por modo de transporte
        if ($request->filled('modo')) {
            $query->where('metodo_transporte', $request->modo);
        }

        // Búsqueda por ID oferta, cliente o ruta
        if ($request->filled('cerca')) {
            $cerca = $request->cerca;
            $query->where(function ($q) use ($cerca) {
                $q->where('oferta_id', 'like', "%$cerca%")
                  ->orWhere('cliente', 'like', "%$cerca%")
                  ->orWhere('ruta', 'like', "%$cerca%");
            });
        }

        $total   = $query->count();
        $perPage = 8;
        $page    = (int) $request->get('page', 1);

        $data = $query
            ->orderByDesc('fecha_pedido')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'data'         => $data,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ]);
    }
}
