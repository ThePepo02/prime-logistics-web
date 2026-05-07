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

    // GET /api/clientes-rol — trae los clientes para el select del formulario
    public function clientes()
    {
        $clientes = Usuarios::where('rol_id', 3)
            ->select('id', 'nom', 'cognoms', 'empresa')
            ->get();

        return response()->json($clientes);
    }
    // POST /api/ofertes — INSERT en la BD (el Create del CRUD)
    public function store(Request $request)
    {

        // 1. Valida los campos obligatorios
        $request->validate([
            'client_id' => 'required|integer',
            'tipus_transport_id' => 'required|integer',
            'tipus_fluxe_id' => 'required|integer',
            'estat_oferta_id' => 'required|integer',
        ]);
        // 2. Crea el registro en la tabla ofertes
        $oferta = Oferta::create([
            'client_id' => $request->client_id,
            'agent_comercial_id' => $request->agent_comercial_id,
            'operador_id' => $request->user()->id, // operador que crea la oferta
            'tipus_transport_id' => $request->tipus_transport_id,
            'tipus_fluxe_id' => $request->tipus_fluxe_id,
            'tipus_carrega_id' => $request->tipus_carrega_id ?? 1, // Valor por defecto si no se envía
            'incoterm_id' => $request->incoterm_id ?? 1, // Valor por defecto si no se envía
            'transportista_id' => $request->transportista_id,
            'port_origen_id' => $request->port_origen_id,
            'port_desti_id' => $request->port_desti_id,
            'pes_brut' => $request->pes_brut ?? 0, // Valor por defecto si no se envía
            'volum' => $request->volum ?? 0, // Valor por defecto si no se envía
            'comentaris' => $request->comentaris,
            'data_validessa_inicial' => $request->data_validessa_inicial,
            'data_validessa_fina' => $request->data_validessa_fina,
            'estat_oferta_id' => $request->estat_oferta_id,
            'data_creacio' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'oferta' => $oferta,
        ], 201); // Devuelve 201 Created
    }
}
