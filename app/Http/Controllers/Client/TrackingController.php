<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\TrackingStep;
use App\Models\Notificacio;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        try {
            $offer_id = $request->query('offer_id');
            $code = $request->query('code');
           
            $oferta = null;
           
            if ($offer_id) {
                $oferta = Oferta::with(['client', 'tipusTransport', 'incoterm', 'port_origen', 'port_desti', 'transportista', 'operador', 'estatOferta'])
                    ->find($offer_id);
            } elseif ($code) {
                $notificacion = Notificacio::where('entitat_tipus', 'envio')
                    ->where(function($q) use ($code) {
                        $q->where('titol', 'like', '%' . $code . '%')
                          ->orWhere('missatge', 'like', '%' . $code . '%');
                    })
                    ->first();
               
                if ($notificacion) {
                    $oferta = Oferta::with(['client', 'tipusTransport', 'incoterm', 'port_origen', 'port_desti', 'transportista', 'operador', 'estatOferta'])
                        ->find($notificacion->entitat_id);
                }
            }
           
            if (!$oferta) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }
           
            $offerCode = $this->extractOfferCodeFromNotification($oferta->id);
            $route = $this->buildRoute($oferta);
            $trackingSteps = TrackingStep::orderBy('ordre', 'asc')->get();
           
            $currentStep = (int)($oferta->tracking_actual ?? 1);
            $totalSteps = $trackingSteps->count();
            $progress = $totalSteps > 0 ? round(($currentStep / $totalSteps) * 100) : 0;
           
            $timeline = $trackingSteps->map(function($step) use ($currentStep) {
                $stepOrder = (int)$step->ordre;
                $isCompleted = $currentStep > $stepOrder;
                $isCurrent = $currentStep === $stepOrder;
               
                return [
                    'title' => $step->nom,
                    'status' => $isCompleted ? 'Completado' : ($isCurrent ? 'En curso' : 'Pendiente'),
                    'date' => '-',
                    'state' => $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending'),
                    'icon' => $isCompleted ? '✓' : ($isCurrent ? '📍' : '⭕'),
                ];
            })->toArray();
           
            return response()->json([
                'id' => $oferta->id, // ← añadido para el botón de avanzar
                'code' => $offerCode ?? 'OC-' . str_pad($oferta->id, 6, '0', STR_PAD_LEFT),
                'route' => $this->formatRoute($oferta),
                'status' => $oferta->estatOferta?->nom ?? 'Pendiente',
                'progress' => $progress,
                'details' => [
                    'shipping_line' => $oferta->transportista?->nom ?? 'Pendiente',
                    'vessel' => 'Pendiente',
                    'container' => 'Pendiente',
                    'incoterm' => $oferta->incoterm?->nom ?? 'Pendiente',
                    'etd' => '-',
                    'eta' => '-',
                    'days_in_transit' => 0,
                ],
                'agent' => [
                    'name' => $oferta->operador?->nom ?? 'Pendiente',
                    'contact' => $oferta->operador?->email ?? '-',
                ],
                'documents' => [],
                'timeline' => $timeline,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error en tracking: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'error' => 'Error al cargar tracking',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ── NUEVO MÉTODO ─────────────────────────────────────────────
    public function advance(Request $request)
    {
        try {
            $oferta = Oferta::find($request->input('offer_id'));

            if (!$oferta) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            $totalSteps = TrackingStep::count();
            $currentStep = (int)($oferta->tracking_actual ?? 1);

            if ($currentStep >= $totalSteps) {
                return response()->json(['error' => 'Ya está en el último paso'], 400);
            }

            $oferta->tracking_actual = $currentStep + 1;
            $oferta->save();

            return response()->json(['tracking_actual' => $oferta->tracking_actual]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function formatRoute($oferta)
    {
        $origen = 'Origen';
        $destino = 'Destino';
       
        if ($oferta->port_origen_id) {
            $origen = $oferta->port_origen?->nom ?? 'Origen';
        }
        if ($oferta->port_desti_id) {
            $destino = $oferta->port_desti?->nom ?? 'Destino';
        }
        if ($oferta->aeroport_origen_id && !$oferta->port_origen_id) {
            $aeroOrigen = \App\Models\Aeroport::find($oferta->aeroport_origen_id);
            $origen = $aeroOrigen?->nom ?? 'Aeropuerto Origen';
        }
        if ($oferta->aeroport_desti_id && !$oferta->port_desti_id) {
            $aeroDesti = \App\Models\Aeroport::find($oferta->aeroport_desti_id);
            $destino = $aeroDesti?->nom ?? 'Aeropuerto Destino';
        }
       
        return ['origin' => $origen, 'destination' => $destino];
    }

    private function buildRoute($oferta)
    {
        $origen = 'Origen';
        $destino = 'Destino';
       
        if ($oferta->port_origen_id) {
            $origen = $oferta->port_origen?->nom ?? 'Origen';
        }
        if ($oferta->port_desti_id) {
            $destino = $oferta->port_desti?->nom ?? 'Destino';
        }
        if ($oferta->aeroport_origen_id && !$oferta->port_origen_id) {
            $aeroOrigen = \App\Models\Aeroport::find($oferta->aeroport_origen_id);
            $origen = $aeroOrigen?->nom ?? 'Aeropuerto Origen';
        }
        if ($oferta->aeroport_desti_id && !$oferta->port_desti_id) {
            $aeroDesti = \App\Models\Aeroport::find($oferta->aeroport_desti_id);
            $destino = $aeroDesti?->nom ?? 'Aeropuerto Destino';
        }
        if ($origen === 'Origen' && $destino === 'Destino' && $oferta->comentaris) {
            return $oferta->comentaris;
        }
       
        return $origen . ' → ' . $destino;
    }

    private function extractOfferCodeFromNotification($oferta_id)
    {
        $notificacion = Notificacio::where('entitat_id', $oferta_id)
            ->where('entitat_tipus', 'envio')
            ->latest('data_creacio')
            ->first();
       
        if ($notificacion && preg_match('/OC-\d{4}-\d{3,}/', $notificacion->titol . ' ' . $notificacion->missatge, $matches)) {
            return $matches[0];
        }
       
        return null;
    }
}