<?php

namespace App\Http\Controllers;

use App\Models\Incoterm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * TipusIncotermController - Controlador para gestionar los Tipos de Incoterm
 * 
 * Este controlador maneja las operaciones CRUD (Create, Read, Update, Delete)
 * sobre los tipos de incoterm de la aplicación.
 * 
 * Rutas disponibles:
 * - GET /api/tipos-incoterm -> Listar todos
 * - GET /api/tipos-incoterm/{id} -> Obtener uno
 * - POST /api/tipos-incoterm -> Crear
 * - PUT /api/tipos-incoterm/{id} -> Actualizar
 * - DELETE /api/tipos-incoterm/{id} -> Eliminar
 */
class TipusIncotermController extends Controller
{
    /**
     * Obtiene la lista de todos los tipos de incoterm
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Obtener todos los incoterms ordenados por nombre
            $incoterms = Incoterm::orderBy('codi', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $incoterms,
                'message' => 'Tipos de incoterm obtenidos correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de incoterm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un tipo de incoterm específico
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $incoterm = Incoterm::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $incoterm,
                'message' => 'Tipo de incoterm obtenido correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de incoterm no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipo de incoterm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo tipo de incoterm
     * 
     * Validación requerida:
     * - codi: obligatorio, string, máximo 10 caracteres, único
     * - nom: obligatorio, string, máximo 255 caracteres
     * - descripcio: opcional, string
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'codi' => 'required|string|max:10|unique:incoterms,codi',
            'nom' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
        ], [
            'codi.required' => 'El código es requerido',
            'codi.unique' => 'El código ya existe en el sistema',
            'nom.required' => 'El nombre es requerido',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Crear el nuevo incoterm
            $incoterm = Incoterm::create([
                'codi' => strtoupper($request->codi), // Guardar código en mayúsculas
                'nom' => $request->nom,
                'descripcio' => $request->descripcio ?? null,
            ]);

            return response()->json([
                'success' => true,
                'data' => $incoterm,
                'message' => 'Tipo de incoterm creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear tipo de incoterm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un tipo de incoterm existente
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $incoterm = Incoterm::findOrFail($id);

            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'codi' => 'required|string|max:10|unique:incoterms,codi,' . $id,
                'nom' => 'required|string|max:255',
                'descripcio' => 'nullable|string',
            ], [
                'codi.required' => 'El código es requerido',
                'codi.unique' => 'El código ya existe en el sistema',
                'nom.required' => 'El nombre es requerido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar el incoterm
            $incoterm->update([
                'codi' => strtoupper($request->codi),
                'nom' => $request->nom,
                'descripcio' => $request->descripcio ?? null,
            ]);

            return response()->json([
                'success' => true,
                'data' => $incoterm,
                'message' => 'Tipo de incoterm actualizado correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de incoterm no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tipo de incoterm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un tipo de incoterm
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $incoterm = Incoterm::findOrFail($id);
            
            // Eliminar el incoterm
            $incoterm->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tipo de incoterm eliminado correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de incoterm no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar tipo de incoterm: ' . $e->getMessage()
            ], 500);
        }
    }
}
