<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncotermsController extends Controller
{
    public function index()
    {
        $incoterms = DB::table('tipus_incoterms')
            ->whereIn('id', range(1, 11))
            ->get();

        $steps = DB::table('tracking_steps')->orderBy('ordre')->get();

        $result = $incoterms->map(function ($incoterm) {
            $pasos = DB::table('incoterms')
                ->where('tipus_inconterm_id', $incoterm->id)
                ->pluck('tracking_steps_id')
                ->toArray();

            return [
                'id'    => $incoterm->id,
                'codi'  => trim($incoterm->codi),
                'nom'   => $incoterm->nom,
                'pasos' => $pasos,
            ];
        });

        $steps = DB::table('tracking_steps')->orderBy('ordre')->get()->map(fn ($s) => [
            'id'    => $s->id,
            'ordre' => $s->ordre,
            'nom'   => $s->nom,
        ]);

        return response()->json([
            'incoterms' => $result,
            'steps'     => $steps,
        ]);
    }

    public function update(Request $request, $id)
    {
        $nuevosPasos   = $request->input('pasos', []);
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
                    ->where('tipus_inconterm_id', $id)
                    ->where('tracking_steps_id', $stepId)
                    ->delete();
            }
        }

        return response()->json(['message' => 'Incoterm actualizado correctamente']);
    }
}