<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TipusIncoterm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncotermsController extends Controller
{
    // Obtener todos los incoterms con sus pasos asignados
    public function index()
    {
        $incoterms = TipusIncoterm::with('incoterms')
            ->whereIn('id', range(1, 11))
            ->get()
            ->map(function ($t) {
                return [
                    'id'    => $t->id,
                    'codi'  => trim($t->codi),
                    'nom'   => $t->nom,
                    'pasos' => $t->incoterms->pluck('tracking_steps_id')->toArray(),
                ];
            });
        $steps = DB::table('tracking_steps')
            ->orderBy('ordre')
            ->get()
            ->map(function ($s) {
                return [
                    'id'    => $s->id,
                    'ordre' => $s->ordre,
                    'nom'   => $s->nom,
                ];
            });

        return response()->json([
            'incoterms' => $incoterms,
            'steps'     => $steps,
        ]);
    }

    // Actualizar los pasos de un incoterm (INSERT y UPDATE)
    public function update(Request $request, $id)
    {
        $nuevosPasos = $request->input('pasos', []);

        $pasosActuales = DB::table('incoterms')
            ->where('tipus_inconterm_id', $id)
            ->pluck('tracking_steps_id')
            ->toArray();

        $añadir = array_diff($nuevosPasos, $pasosActuales);
        $borrar = array_diff($pasosActuales, $nuevosPasos);

        foreach ($añadir as $stepId) {
            DB::table('incoterms')->insert([
                'tipus_inconterm_id' => $id,
                'tracking_steps_id'  => $stepId,
            ]);
        }

        foreach ($borrar as $stepId) {
            $incoterm = DB::table('incoterms')
                ->where('tipus_inconterm_id', $id)
                ->where('tracking_steps_id', $stepId)
                ->first();

            if (!$incoterm) continue;

            $enUso = DB::table('ofertes')
                ->where('incoterm_id', $incoterm->id)
                ->exists();

            if (!$enUso) {
                DB::table('incoterms')
                    ->where('id', $incoterm->id)
                    ->delete();
            }
        }

        return response()->json(['message' => 'Incoterm actualizado correctamente']);
    }
}
