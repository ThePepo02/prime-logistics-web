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
        $pasos = $request->input('pasos', []);

        // Borrar los pasos actuales de este incoterm
        DB::table('incoterms')->where('tipus_inconterm_id', $id)->delete();

        // Insertar los nuevos pasos
        foreach ($pasos as $stepId) {
            DB::table('incoterms')->insert([
                'tipus_inconterm_id' => $id,
                'tracking_steps_id' => $stepId,
            ]);
        }

        return response()->json(['message' => 'Incoterm actualizado correctamente']);
    }
}