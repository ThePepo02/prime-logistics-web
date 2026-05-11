<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    // GET /api/ofertes — devuelve la lista paginada de ofertas para la tabla
    // Carga las relaciones client, estatOferta y tipusTransport en una sola query
    // para no hacer N consultas a la BD (una por cada oferta)
    public function index()
    {
        $ofertes = Oferta::with(['client', 'estatOferta', 'tipusTransport'])
            ->orderBy('id', 'desc') // más recientes primero
            ->paginate(8);          // 8 registros por página

        return response()->json($ofertes);
    }

    // GET /api/clientes-rol — devuelve los usuarios con rol_id = 3 (clientes)
    // Lo usa PasoIdentificacion.vue para llenar el select de clientes
    public function clientes()
    {
        $clientes = Usuarios::where('rol_id', 3)
            ->select('id', 'nom', 'cognoms', 'empresa')
            ->get();

        return response()->json($clientes);
    }

    // POST /api/ofertes — INSERT en la BD (el Create del CRUD)
    // Lo llama NuevoPedido.vue al pulsar "Enviar Pedido" o "Guardar Borrador"
    public function store(Request $request)
    {
        // 1. Valida los campos obligatorios — si faltan, Laravel devuelve 422 automáticamente
        $request->validate([
            'client_id'          => 'required|integer',
            'tipus_transport_id' => 'required|integer',
            'tipus_fluxe_id'     => 'required|integer',
            'estat_oferta_id'    => 'required|integer',
        ]);

        // 2. Crea el registro en la tabla ofertes con los datos del formulario
        // ?? significa "si es null, usa este valor por defecto"
        $oferta = Oferta::create([
            'client_id'              => $request->client_id,
            'agent_comercial_id'     => $request->agent_comercial_id,
            'operador_id'            => $request->user()->id, // el operador autenticado que crea la oferta
            'tipus_transport_id'     => $request->tipus_transport_id,
            'tipus_fluxe_id'         => $request->tipus_fluxe_id,
            'tipus_carrega_id'       => $request->tipus_carrega_id       ?? 1,
            'incoterm_id'            => null,
            'transportista_id'       => $request->transportista_id,
            'port_origen_id'         => $request->port_origen_id,
            'port_desti_id'          => $request->port_desti_id,
            'pes_brut'               => $request->pes_brut               ?? 0,
            'volum'                  => $request->volum                  ?? 0,
            'comentaris'             => $request->comentaris,
            'data_validessa_inicial' => $request->data_validessa_inicial,
            'data_validessa_fina'    => $request->data_validessa_fina,
            'estat_oferta_id'        => $request->estat_oferta_id,
            'tipus_validacio_id'     => $request->tipus_validacio_id     ?? 1, // 1 = Pendent
            'data_creacio'           => now(), // fecha y hora actual del servidor
        ]);

        // 3. Devuelve la oferta creada con código 201 Created
        return response()->json([
            'ok'     => true,
            'oferta' => $oferta,
        ], 201);
    }

    // GET /api/ports — devuelve la lista de puertos para los selects
    // Lo usa PasoRutaCierre.vue para Puerto Origen y Puerto Destino
    public function ports()
    {
        $ports = \App\Models\Port::select('id', 'nom')->get();
        return response()->json($ports);
    }

    // GET /api/tipus-carrega — devuelve los tipos de carga para el select
    // Lo usa PasoEspecificaciones.vue para el campo Tipo de Carga
    public function tipusCarrega()
    {
        $tipus = \App\Models\TipusCarrega::select('id', 'tipus')->get();
        return response()->json($tipus);
    }

    // GET /api/transportistes — devuelve los agentes de transporte para el select
    // Lo usa PasoRutaCierre.vue para el campo Agente Aduanal
    public function transportistes()
    {
        $data = \App\Models\Transportista::select('id', 'nom')->get();
        return response()->json($data);
    }

    // GET /api/tipus-incoterms — devuelve los incoterms para el select
    // Lo usa PasoRutaCierre.vue para el campo Incoterm
    public function tipusIncoterm()
    {
        $data = \App\Models\TipusIncoterm::select('id', 'codi', 'nom')->get();
        return response()->json($data);
    }
}
