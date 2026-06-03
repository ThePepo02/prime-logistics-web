<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\TrackingStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * TrackingOfertaController - Controlador para gestionar el tracking de ofertas
 * 
 * Este controlador permite visualizar y actualizar el paso del tracking
 * en el que se encuentra una oferta.
 */
class TrackingOfertaController extends Controller
{
    /**
     * Obtiene el tracking de una oferta específica
     * 
     * @param int $ofertaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($ofertaId)
    {
        try {
            // Verificar que la oferta existe
            $oferta = Oferta::findOrFail($ofertaId);

            // Obtener todos los pasos de tracking de la oferta
            $trackingSteps = TrackingStep::where('oferta_id', $ofertaId)
                ->orderBy('ordre', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'oferta' => $oferta,
                    'tracking_steps' => $trackingSteps,
                ],
                'message' => 'Tracking obtenido correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tracking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza el paso actual del tracking de una oferta
     * 
     * Parámetros requeridos:
     * - oferta_id: ID de la oferta
     * - tracking_step_id: ID del paso de tracking a activar
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCurrentStep(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'oferta_id' => 'required|integer|exists:ofertes,id',
            'tracking_step_id' => 'required|integer|exists:tracking_steps,id',
        ], [
            'oferta_id.required' => 'El ID de la oferta es requerido',
            'oferta_id.exists' => 'La oferta no existe',
            'tracking_step_id.required' => 'El ID del paso es requerido',
            'tracking_step_id.exists' => 'El paso de tracking no existe',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oferta = Oferta::findOrFail($request->oferta_id);
            $trackingStep = TrackingStep::findOrFail($request->tracking_step_id);

            // Verificar que el paso pertenece a la oferta
            if ($trackingStep->oferta_id !== $oferta->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El paso de tracking no pertenece a esta oferta'
                ], 400);
            }

            // Actualizar el estado de la oferta según el tracking
            $oferta->update([
                'tracking_actual' => $trackingStep->ordre,
                'estat_oferta_id' => $trackingStep->estat_id ?? $oferta->estat_oferta_id,
            ]);

            // Registrar la actualización del tracking
            // (En una aplicación real, aquí iríamos registrando el cambio con fecha/hora)

            return response()->json([
                'success' => true,
                'data' => [
                    'oferta' => $oferta,
                    'tracking_step' => $trackingStep,
                ],
                'message' => 'Paso de tracking actualizado correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta o paso de tracking no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tracking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la lista de pasos disponibles para un tipo de incoterm
     * 
     * @param int $incotermId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSteps($incotermId)
    {
        try {
            // Obtener los pasos de tracking disponibles
            $steps = TrackingStep::where('incoterm_id', $incotermId)
                ->orderBy('ordre', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $steps,
                'message' => 'Pasos disponibles obtenidos correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pasos: ' . $e->getMessage()
            ], 500);
        }
    }
}
