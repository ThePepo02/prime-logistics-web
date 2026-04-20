<?php

namespace App\Http\Controllers;

use App\Models\Notificacio;
use Illuminate\Http\Request;

class NotificacionsController extends Controller
{
    // GET /api/notificacions
    public function index(Request $request)
    {
        $usuariId  = $request->user()->id;
        $filtre    = $request->query('filtre', 'totes');
        $pagina    = (int) $request->query('pagina', 1);
        $perPagina = 6;

        $query = Notificacio::where('usuari_id', $usuariId)
                            ->orderBy('data_creacio', 'desc');

        if ($filtre === 'no_llegides') {
            $query->where('llegida', 0);
        } elseif ($filtre === 'ofertes') {
            $query->whereIn('tipus', ['nova_solicitud', 'oferta_acceptada', 'oferta_rebutjada']);
        } elseif ($filtre === 'sistema') {
            $query->whereIn('tipus', ['alerta_operacio', 'fase_actualitzada', 'document_revisio']);
        }

        $total         = $query->count();
        $notificacions = $query->skip(($pagina - 1) * $perPagina)->take($perPagina)->get();

        return response()->json([
            'notificacions' => $notificacions,
            'total'         => $total,
            'pagina'        => $pagina,
            'total_pagines' => (int) ceil($total / $perPagina),
            'no_llegides'   => Notificacio::where('usuari_id', $usuariId)->where('llegida', 0)->count(),
        ]);
    }

    // PUT /api/notificacions/{id}/llegir
    public function marcarLlegida($id, Request $request)
    {
        $notificacio = Notificacio::where('id', $id)
                                  ->where('usuari_id', $request->user()->id)
                                  ->firstOrFail();
        $notificacio->llegida = true;
        $notificacio->save();

        return response()->json(['ok' => true]);
    }

    // PUT /api/notificacions/marcar-totes
    public function marcarTotes(Request $request)
    {
        Notificacio::where('usuari_id', $request->user()->id)
                   ->where('llegida', 0)
                   ->update(['llegida' => 1]);

        return response()->json(['ok' => true]);
    }
}
