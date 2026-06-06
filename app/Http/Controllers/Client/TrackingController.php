<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        try {
            $oferta_id = $request->query('offer_id') ?? $request->query('code');

            if (!$oferta_id) {
                return response()->json(['error' => 'Código de oferta requerido'], 400);
            }

            $envio = Envio::where('oferta_id', $oferta_id)->first();

            if (!$envio) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            // El incoterm está guardado en envios.incoterm (ej: "CIF", "FOB")
            // Buscamos el tipus_incoterm por el código
            $tipusIncoterm = DB::table('tipus_incoterms')
                ->where(DB::raw('TRIM(codi)'), trim($envio->incoterm))
                ->first();

            // Cogemos los pasos del incoterm de este envio
            if ($tipusIncoterm) {
                $trackingSteps = DB::table('incoterms')
                    ->join('tracking_steps', 'incoterms.tracking_steps_id', '=', 'tracking_steps.id')
                    ->where('incoterms.tipus_inconterm_id', $tipusIncoterm->id)
                    ->orderBy('tracking_steps.ordre')
                    ->select('tracking_steps.id', 'tracking_steps.ordre', 'tracking_steps.nom')
                    ->get();
            } else {
                // Si no tiene incoterm asignado, mostramos todos los pasos
                $trackingSteps = DB::table('tracking_steps')->orderBy('ordre')->get();
            }

            $currentStep = (int)($envio->tracking_actual ?? 1);
            $totalSteps = $trackingSteps->count();
            $progress = $totalSteps > 0 ? round(($currentStep / $totalSteps) * 100) : 0;

            $timeline = $trackingSteps->values()->map(function ($step, $index) use ($currentStep) {
                $stepNum = $index + 1;
                $isCompleted = $currentStep > $stepNum;
                $isCurrent   = $currentStep === $stepNum;

                return [
                    'title'  => $step->nom,
                    'status' => $isCompleted ? 'Completado' : ($isCurrent ? 'En curso' : 'Pendiente'),
                    'date'   => '-',
                    'state'  => $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending'),
                    'icon'   => $isCompleted ? '✓' : ($isCurrent ? '📍' : '⭕'),
                ];
            })->toArray();

            $pasoActual = $trackingSteps->values()->get($currentStep - 1);
            $estadoActual = $pasoActual ? $pasoActual->nom : 'En preparación';

            return response()->json([
                'id'       => $envio->oferta_id,
                'code'     => $envio->oferta_id,
                'route'    => [
                    'origin'      => $envio->origen,
                    'destination' => $envio->destino,
                ],
                'status'   => $estadoActual,
                'progress' => $progress,
                'details'  => [
                    'shipping_line'   => $envio->compania ?? 'Pendiente',
                    'vessel'          => 'Pendiente',
                    'container'       => 'Pendiente',
                    'incoterm'        => trim($envio->incoterm) ?? 'Pendiente',
                    'etd'             => '-',
                    'eta'             => '-',
                    'days_in_transit' => 0,
                ],
                'agent'    => [
                    'name'    => $envio->cliente ?? 'Pendiente',
                    'contact' => '-',
                ],
                'documents' => [],
                'timeline'  => $timeline,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en tracking: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Error al cargar tracking',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function advance(Request $request)
    {
        try {
            $envio = Envio::where('oferta_id', $request->input('offer_id'))->first();

            if (!$envio) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            $totalSteps = $this->contarPasos($envio);
            $currentStep = (int)($envio->tracking_actual ?? 1);

            if ($currentStep >= $totalSteps) {
                return response()->json(['error' => 'Ya está en el último paso'], 400);
            }

            $envio->tracking_actual = $currentStep + 1;
            $envio->save();

            return response()->json(['tracking_actual' => $envio->tracking_actual]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function previous(Request $request)
    {
        try {
            $envio = Envio::where('oferta_id', $request->input('offer_id'))->first();

            if (!$envio) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            $currentStep = (int)($envio->tracking_actual ?? 1);

            if ($currentStep <= 1) {
                return response()->json(['error' => 'Ya está en el primer paso'], 400);
            }

            $envio->tracking_actual = $currentStep - 1;
            $envio->save();

            return response()->json(['tracking_actual' => $envio->tracking_actual]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Cuenta los pasos según el incoterm del envio
    private function contarPasos(Envio $envio): int
    {
        $tipusIncoterm = DB::table('tipus_incoterms')
            ->where(DB::raw('TRIM(codi)'), trim($envio->incoterm ?? ''))
            ->first();

        if ($tipusIncoterm) {
            return DB::table('incoterms')
                ->where('tipus_inconterm_id', $tipusIncoterm->id)
                ->count();
        }

        return DB::table('tracking_steps')->count();
    }
}