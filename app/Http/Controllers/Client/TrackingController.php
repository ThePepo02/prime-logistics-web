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
            // Buscar oferta por offer_id o code
            $offer_id = $request->query('offer_id');
            $code = $request->query('code');
           
            $oferta = null;
           
            if ($offer_id) {
                // Buscar por ID directo (más rápido)
                $oferta = Oferta::with(['client', 'tipusTransport', 'incoterm', 'port_origen', 'port_desti', 'transportista', 'operador', 'estatOferta'])
                    ->find($offer_id);
            } elseif ($code) {
                // Buscar por código (OC-2024-018) buscando en notificaciones
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
                return response()->json([
                    'error' => 'Oferta no encontrada'
                ], 404);
            }
           
            // Obtener el código real de las notificaciones
            $offerCode = $this->extractOfferCodeFromNotification($oferta->id);
           
            // Construir la ruta (puertos para marítimo, aeropuertos para aéreo)
            $route = $this->buildRoute($oferta);
           
            // Obtener todos los tracking steps disponibles
            $trackingSteps = TrackingStep::orderBy('ordre', 'asc')->get();
           
            // Construir la respuesta
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
                'code' => $offerCode ?? 'OC-' . str_pad($oferta->id, 6, '0', STR_PAD_LEFT),
                'route' => $route,
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


    /**
     * Construye la ruta considerando tanto puertos como aeropuertos
     */
    private function buildRoute($oferta)
    {
        $origen = 'Origen';
        $destino = 'Destino';
       
        // Si tiene puertos (marítimo)
        if ($oferta->port_origen_id) {
            $origen = $oferta->port_origen?->nom ?? 'Origen';
        }
        if ($oferta->port_desti_id) {
            $destino = $oferta->port_desti?->nom ?? 'Destino';
        }
       
        // Si tiene aeropuertos (aéreo)
        if ($oferta->aeroport_origen_id && !$oferta->port_origen_id) {
            $aeroOrigen = \App\Models\Aeroport::find($oferta->aeroport_origen_id);
            $origen = $aeroOrigen?->nom ?? 'Aeropuerto Origen';
        }
        if ($oferta->aeroport_desti_id && !$oferta->port_desti_id) {
            $aeroDesti = \App\Models\Aeroport::find($oferta->aeroport_desti_id);
            $destino = $aeroDesti?->nom ?? 'Aeropuerto Destino';
        }
       
        // Fallback al comentario si no hay rutas especificadas
        if ($origen === 'Origen' && $destino === 'Destino' && $oferta->comentaris) {
            return $oferta->comentaris;
        }
       
        return $origen . ' → ' . $destino;
    }


    /**
     * Extrae el código real de la oferta de las notificaciones
     */
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









