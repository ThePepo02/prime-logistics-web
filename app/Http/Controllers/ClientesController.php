<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    // GET /api/clientes — devuelve todos los usuarios con sus ofertas contadas
    public function index()
    {
        $clientes = Usuarios::withCount('ofertes')
            ->get()
            ->map(function ($u) {
                return [
                    'id'       => $u->id,
                    'empresa'  => $u->empresa,
                    'cif'      => $u->cif ?? '-',
                    'contacto' => $u->nom . ' ' . $u->cognoms,
                    'email'    => $u->correu,
                    'telefono' => $u->telefon ?? '-',
                    'ofertas'  => $u->ofertes_count,
                    'activo'   => (bool) $u->actiu,
                ];
            });

        return response()->json($clientes);
    }

    // PUT /api/clientes/{id}/estado — activa o desactiva un cliente
    public function toggleEstado($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->actiu = !$usuario->actiu;
        $usuario->save();

        return response()->json(['activo' => (bool) $usuario->actiu]);
    }
}
