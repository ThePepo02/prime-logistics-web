<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDataController extends Controller
{
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'offer_id' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'client_name' => ['nullable', 'string', 'max:150'],
            'transport_mode' => ['required', 'string', 'max:50'],
            'origin' => ['required', 'string', 'max:120'],
            'destination' => ['required', 'string', 'max:120'],
            'cargo_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'incoterm' => ['nullable', 'string', 'max:20'],
            'urgency' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:150'],
            'client_id' => ['nullable', 'integer'],
        ]);

        $client = $this->resolveClient($validated['client_id'] ?? null);

        $offerId = trim((string) ($validated['offer_id'] ?? ''));
        if ($offerId === '' || $offerId === 'OC-2025-') {
            $offerId = 'OFR-' . now()->format('Y') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        $clientFullName = trim(($client->nom ?? '') . ' ' . ($client->cognoms ?? ''));

        $newId = DB::table('envios')->insertGetId([
            'origen' => $validated['origin'],
            'destino' => $validated['destination'],
            'estado_envio' => $this->normalizeShipmentStatusForInsert($validated['status'] ?? null),
            'oferta_id' => $offerId,
            'contenido_envio' => $validated['description'] ?: ($validated['cargo_type'] ?? 'Sin descripcion'),
            'metodo_transporte' => $this->normalizeTransportModeForInsert($validated['transport_mode']),
            'tipo_divisa' => 'EUR',
            'fecha_pedido' => now()->toDateString(),
            'cliente' => $clientFullName !== '' ? $clientFullName : ($validated['client_name'] ?? 'Cliente'),
            'ruta' => $validated['origin'] . ' -> ' . $validated['destination'],
            'peso_kg' => (float) ($validated['weight_kg'] ?? 0),
            'incoterm' => $validated['incoterm'] ?? 'N/A',
            'urgencia' => $validated['urgency'] ?? 'Media',
            'compania' => $validated['company'] ?? ($client->empresa ?? 'Sin empresa'),
            'cliente_id' => $client->id ?? ($validated['client_id'] ?? null),
        ]);

        return response()->json([
            'message' => 'Pedido creado correctamente',
            'id' => $newId,
            'offer_id' => $offerId,
        ], 201);
    }

    public function dashboard(): JsonResponse
    {
        $shipments = DB::table('envios')
            ->leftJoin('usuaris as clients', function ($join) {
                $join->on('clients.id', '=', 'envios.cliente_id')
                    ->where('clients.rol_id', '=', 3);
            })
            ->select([
                'envios.*',
                'clients.nom as client_nom',
                'clients.cognoms as client_cognoms',
                'clients.empresa as client_empresa',
            ])
            ->get();

        $active = $shipments->filter(fn ($row) => str_contains(mb_strtolower((string) $row->estado_envio), 'tr'));
        $delivered = $shipments->filter(fn ($row) => str_contains(mb_strtolower((string) $row->estado_envio), 'entreg'));
        $prep = $shipments->filter(fn ($row) => str_contains(mb_strtolower((string) $row->estado_envio), 'prepar'));

        $recentOrders = DB::table('envios')
            ->leftJoin('usuaris as clients', function ($join) {
                $join->on('clients.id', '=', 'envios.cliente_id')
                    ->where('clients.rol_id', '=', 3);
            })
            ->select([
                'envios.*',
                'clients.nom as client_nom',
                'clients.cognoms as client_cognoms',
                'clients.empresa as client_empresa',
            ])
            ->orderByDesc('envios.id')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $clientName = trim((string) (($row->client_nom ?? '') . ' ' . ($row->client_cognoms ?? '')));

                return [
                    'id' => $row->oferta_id ?: ('ENV-' . $row->id),
                    'mode' => $row->metodo_transporte ?: 'Sin definir',
                    'route' => $row->origen . ' -> ' . $row->destino,
                    'date' => $row->fecha_pedido,
                    'status' => $this->normalizeShipmentStatus($row->estado_envio),
                    'client' => $clientName !== '' ? $clientName : ($row->client_empresa ?: $row->cliente),
                ];
            })
            ->values();

        $chartData = DB::table('envios')
            ->selectRaw('fecha_pedido as day, COUNT(*) as total')
            ->whereNotNull('fecha_pedido')
            ->groupBy('fecha_pedido')
            ->orderByDesc('fecha_pedido')
            ->limit(8)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($row) {
                $count = (int) $row->total;

                return [
                    'pedidos' => min(80, 20 + ($count * 10)),
                    'incidencias' => max(6, (int) round($count * 3)),
                ];
            });

        return response()->json([
            'kpis' => [
                'active' => $active->count(),
                'delivered' => $delivered->count(),
                'delayed' => max(0, $shipments->count() - $active->count() - $delivered->count() - $prep->count()),
                'incidents' => $prep->count(),
            ],
            'recentOrders' => $recentOrders,
            'chartData' => $chartData,
        ]);
    }

    public function orders(Request $request): JsonResponse
    {
        $query = DB::table('envios')
            ->leftJoin('usuaris as clients', function ($join) {
                $join->on('clients.id', '=', 'envios.cliente_id')
                    ->where('clients.rol_id', '=', 3);
            })
            ->select([
                'envios.*',
                'clients.nom as client_nom',
                'clients.cognoms as client_cognoms',
                'clients.empresa as client_empresa',
            ]);

        if ($request->filled('q')) {
            $q = (string) $request->string('q');
            $query->where(function ($inner) use ($q) {
                $inner->where('oferta_id', 'like', "%{$q}%")
                    ->orWhere('origen', 'like', "%{$q}%")
                    ->orWhere('destino', 'like', "%{$q}%")
                    ->orWhere('cliente', 'like', "%{$q}%")
                    ->orWhere('clients.nom', 'like', "%{$q}%")
                    ->orWhere('clients.cognoms', 'like', "%{$q}%")
                    ->orWhere('clients.empresa', 'like', "%{$q}%");
            });
        }

        if ($request->filled('mode')) {
            $query->where('metodo_transporte', $request->string('mode'));
        }

        if ($request->filled('status')) {
            $status = (string) $request->string('status');

            if ($status === 'COMPLETADA') {
                $query->where('estado_envio', 'like', '%entreg%');
            } elseif ($status === 'EN TRANSITO') {
                $query->where('estado_envio', 'like', '%tr%');
            } elseif ($status === 'EN PREPARACION') {
                $query->where('estado_envio', 'like', '%prepar%');
            }
        }

        $orders = $query->orderByDesc('envios.id')->paginate(8);

        $mapped = collect($orders->items())->map(function ($row) {
            $clientName = trim((string) (($row->client_nom ?? '') . ' ' . ($row->client_cognoms ?? '')));

            return [
                'id' => $row->oferta_id ?: ('ENV-' . $row->id),
                'ref' => 'CL-' . str_pad((string) $row->cliente_id, 3, '0', STR_PAD_LEFT),
                'mode' => $row->metodo_transporte ?: 'Sin definir',
                'route' => $row->origen . ' -> ' . $row->destino,
                'weight' => number_format((float) $row->peso_kg, 0, ',', '.') . 'kg',
                'date' => $row->fecha_pedido,
                'etd' => $row->fecha_pedido,
                'status' => $this->normalizeShipmentStatus($row->estado_envio),
                'client' => $clientName !== '' ? $clientName : ($row->client_empresa ?: $row->cliente),
            ];
        })->values();

        return response()->json([
            'data' => $mapped,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
            ],
        ]);
    }

    public function tracking(Request $request): JsonResponse
    {
        $code = (string) $request->string('code', '');

        $query = DB::table('envios')
            ->leftJoin('usuaris as clients', function ($join) {
                $join->on('clients.id', '=', 'envios.cliente_id')
                    ->where('clients.rol_id', '=', 3);
            })
            ->select([
                'envios.*',
                'clients.nom as client_nom',
                'clients.cognoms as client_cognoms',
                'clients.empresa as client_empresa',
            ]);
        if ($code !== '') {
            $query->where('oferta_id', $code);
        }

        $shipment = $query->orderByDesc('envios.id')->first();

        if (!$shipment) {
            return response()->json(['message' => 'No se encontro envio'], 404);
        }

        $steps = DB::table('tracking_steps')->orderBy('ordre')->get();

        $currentStepOrder = match ($this->normalizeShipmentStatus($shipment->estado_envio)) {
            'COMPLETADA' => 9,
            'EN TRANSITO' => 5,
            'EN PREPARACION' => 2,
            default => 3,
        };

        $timeline = $steps->map(function ($step) use ($currentStepOrder) {
            $order = (int) $step->ordre;
            $state = 'pending';

            if ($order < $currentStepOrder) {
                $state = 'done';
            } elseif ($order === $currentStepOrder) {
                $state = 'current';
            }

            return [
                'icon' => $this->iconForStep($order),
                'title' => $step->nom,
                'status' => $state === 'done' ? 'COMPLETADA' : ($state === 'current' ? 'EN CURSO' : 'PENDIENTE'),
                'date' => $state === 'pending' ? 'Pendiente' : 'Actualizado',
                'state' => $state,
            ];
        })->values();

        $progress = max(10, min(100, (int) round(($currentStepOrder / 9) * 100)));

        return response()->json([
            'code' => $shipment->oferta_id ?: ('ENV-' . $shipment->id),
            'route' => $shipment->origen . ' -> ' . $shipment->destino,
            'status' => $this->normalizeShipmentStatus($shipment->estado_envio),
            'progress' => $progress,
            'client' => trim((string) (($shipment->client_nom ?? '') . ' ' . ($shipment->client_cognoms ?? ''))) ?: ($shipment->client_empresa ?: $shipment->cliente),
            'details' => [
                'shipping_line' => 'Pendiente',
                'vessel' => 'Pendiente',
                'container' => 'Pendiente',
                'incoterm' => $shipment->incoterm,
                'etd' => $shipment->fecha_pedido,
                'eta' => $shipment->fecha_pedido,
                'days_in_transit' => 34,
            ],
            'agent' => [
                'name' => 'Aduana Express SL',
                'contact' => '+34 96 123 4567 - info@aduanaexpress.es',
            ],
            'documents' => [
                'B/L (Bill of Lading)',
                'Factura Comercial',
                'Packing List',
            ],
            'timeline' => $timeline,
        ]);
    }

    public function notifications(): JsonResponse
    {
        $rows = DB::table('ofertes')
            ->leftJoin('estats_ofertes', 'estats_ofertes.id', '=', 'ofertes.estat_oferta_id')
            ->leftJoin('tipus_transports', 'tipus_transports.id', '=', 'ofertes.tipus_transport_id')
            ->leftJoin('usuaris as clients', function ($join) {
                $join->on('clients.id', '=', 'ofertes.client_id')
                    ->where('clients.rol_id', '=', 3);
            })
            ->select([
                'ofertes.id',
                'ofertes.comentaris',
                'ofertes.data_creacio',
                'estats_ofertes.estat as estat_oferta',
                'tipus_transports.tipus as transport_tipus',
                'clients.nom as client_nom',
                'clients.cognoms as client_cognoms',
                'clients.empresa as client_empresa',
            ])
            ->orderByDesc('ofertes.id')
            ->limit(12)
            ->get();

        $notifications = $rows->map(function ($row, $index) {
            $status = (string) ($row->estat_oferta ?? 'Enviada');
            $clientName = trim((string) (($row->client_nom ?? '') . ' ' . ($row->client_cognoms ?? '')));
            $typeClass = match (mb_strtolower($status)) {
                'acceptada' => 'ok',
                'rebutjada' => 'no',
                default => 'sent',
            };

            return [
                'id' => $row->id,
                'title' => "Oferta OC-{$row->id} {$status}",
                'message' => $row->comentaris ?: 'Actualizacion de oferta registrada en el sistema.',
                'code' => 'OC-' . str_pad((string) $row->id, 6, '0', STR_PAD_LEFT),
                'type' => $status,
                'typeClass' => $typeClass,
                'time' => $row->data_creacio ?: 'Sin fecha',
                'primary' => $index % 2 === 0 ? 'Ver Oferta' : 'Ver Tracking',
                'secondary' => $index === 0 ? 'Rechazar' : null,
                'featured' => $index === 0,
                'client' => $clientName !== '' ? $clientName : ($row->client_empresa ?: 'Cliente'),
                'transport' => $row->transport_tipus ?: 'Sin definir',
            ];
        })->values();

        $unread = min(3, $notifications->count());

        return response()->json([
            'notices' => $notifications,
            'stats' => [
                'unread' => $unread,
                'pending_action' => $notifications->where('typeClass', 'sent')->count(),
                'resolved_month' => $notifications->whereIn('typeClass', ['ok', 'no'])->count(),
                'total' => $notifications->count(),
            ],
            'priority' => $notifications->first(),
        ]);
    }

    private function normalizeShipmentStatus(?string $status): string
    {
        $value = mb_strtolower((string) $status);

        if (str_contains($value, 'entreg')) {
            return 'COMPLETADA';
        }

        if (str_contains($value, 'tr')) {
            return 'EN TRANSITO';
        }

        if (str_contains($value, 'prepar')) {
            return 'EN PREPARACION';
        }

        return 'ACEPTADA';
    }

    private function iconForStep(int $order): string
    {
        return match ($order) {
            1 => '📦',
            2 => '🚚',
            3 => '📄',
            4 => '⚓',
            5 => '🚢',
            6 => '⚓',
            7 => '📋',
            8 => '🚛',
            default => '📍',
        };
    }

    private function resolveClient(?int $clientId): ?object
    {
        if ($clientId) {
            $selected = DB::table('usuaris')
                ->where('id', $clientId)
                ->where('rol_id', 3)
                ->first();

            if ($selected) {
                return $selected;
            }
        }

        return DB::table('usuaris')
            ->where('rol_id', 3)
            ->orderBy('id')
            ->first();
    }

    private function normalizeTransportModeForInsert(string $transportMode): string
    {
        $value = mb_strtolower(trim($transportMode));

        return match ($value) {
            'maritimo', 'marítimo' => 'Marítimo',
            'aereo', 'aéreo' => 'Aéreo',
            'terrestre' => 'Terrestre',
            'ferroviario' => 'Ferroviario',
            // El formulario tiene opcion multimodal, pero envios no la acepta por CHECK.
            'multimodal' => 'Terrestre',
            default => 'Terrestre',
        };
    }

    private function normalizeShipmentStatusForInsert(?string $status): string
    {
        $value = mb_strtolower(trim((string) $status));

        return match ($value) {
            'borrador', 'en preparación', 'en preparacion' => 'En preparación',
            'en tránsito', 'en transito' => 'En tránsito',
            'entregado hoy' => 'Entregado hoy',
            default => 'En preparación',
        };
    }
}
