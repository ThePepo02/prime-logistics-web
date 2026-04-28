<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Usuarios;
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


    // GET /api/clientes-rol — usuarios con rol cliente (rol_id = 3)
    public function clientes()
    {
        $clientes = Usuarios::where('rol_id', 3)
            ->select('id', 'nom', 'cognoms', 'empresa')
            ->get();

        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'tipus_transport_id' => 'required|integer',
            'tipus_fluxe_id' => 'required|integer',
            'estat_oferta_id' => 'required|integer',
        ]);

        $oferta = Oferta::create([
            'client_id' => $request->client_id,
            'agent_comercial_id' => $request->agent_comercial_id,
            'operador_id' => $request->user()->id,
            'tipus_transport_id' => $request->tipus_transport_id,
            'tipus_fluxe_id' => $request->tipus_fluxe_id,
            'tipus_carrega_id' => $request->tipus_carrega_id,
            'incoterm_id' => $request->incoterm_id,
            'tipus_contenedor_id' => $request->tipus_contenedor_id,
            'transportista_id' => $request->transportista_id,
            'port_origen_id' => $request->port_origen_id,
            'port_destin_id' => $request->port_destin_id,
            'pes_brut' => $request->pes_brut,
            'volum' => $request->volum,
            'comentaris' => $request->comentaris,
            'data_validessa_inicial' => $request->data_validessa_inicial,
            'data_validessa_final' => $request->data_validessa_final,
            'estat_oferta_id' => $request->estat_oferta_id,
            'data_creacio' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'oferta' => $oferta,
        ], 201);
    }
}
