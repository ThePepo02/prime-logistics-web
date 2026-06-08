<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncotermsController extends Controller
{
    // Obtener todos los incoterms con sus pasos asignados
    public function index()
    {
        $incoterms = DB::table('tipus_incoterms')
            ->whereIn('id', range(1, 11)) // solo los originales sin duplicados
            ->get();

        $steps = DB::table('tracking_steps')->orderBy('ordre')->get();

        $result = $incoterms->map(function($incoterm) {
            $pasos = DB::table('incoterms')
                ->where('tipus_inconterm_id', $incoterm->id)
                ->pluck('tracking_steps_id')
                ->toArray();

            return [
                'id' => $incoterm->id,
                'codi' => trim($incoterm->codi),
                'nom' => $incoterm->nom,
                'pasos' => $pasos,
            ];
        });

        $steps = DB::table('tracking_steps')->orderBy('ordre')->get()->map(fn($s) => [
            'id' => $s->id,
            'ordre' => $s->ordre,
            'nom' => $s->nom,
        ]);

        return response()->json([
            'incoterms' => $result,
            'steps' => $steps,
        ]);
    }

    // Actualizar los pasos de un incoterm (INSERT y UPDATE)
    public function update(Request $request, $id)
{
    $nuevosPasos = $request->input('pasos', []);

    // Pasos que tiene actualmente este incoterm
    $pasosActuales = DB::table('incoterms')
        ->where('tipus_inconterm_id', $id)
        ->pluck('tracking_steps_id')
        ->toArray();

    // Pasos que hay que añadir (están en nuevos pero no en actuales)
    $añadir = array_diff($nuevosPasos, $pasosActuales);

    // Pasos que hay que borrar (están en actuales pero no en nuevos)
    // Solo borramos los que NO estén referenciados en ofertes
    $borrar = array_diff($pasosActuales, $nuevosPasos);

    // Insertar los nuevos
    foreach ($añadir as $stepId) {
        DB::table('incoterms')->insert([
            'tipus_inconterm_id' => $id,
            'tracking_steps_id'  => $stepId,
        ]);
    }

    // Borrar solo los que no tienen ofertas vinculadas
    foreach ($borrar as $stepId) {
        $enUso = DB::table('ofertes')
            ->where('incoterm_id', $stepId)
            ->exists();

        if (!$enUso) {
            DB::table('incoterms')
                ->where('tipus_inconterm_id', $id)
                ->where('tracking_steps_id', $stepId)
                ->delete();
        }
    }

    return response()->json(['message' => 'Incoterm actualizado correctamente']);
}
}