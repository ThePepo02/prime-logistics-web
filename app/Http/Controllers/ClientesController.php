<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    // GET /api/clientes — devuelve todos los clientes con sus ofertas contadas
    public function index()
    {
        $clientes = Usuarios::withCount('ofertes')
            ->where('rol_id', 3) // solo clientes
            ->get()
            ->map(function ($u) {
                return [
                    'id'       => $u->id,
                    'empresa'  => $u->empresa,
                    'cif'      => $u->cif ?? '-',
                    'contacto' => $u->nom . ' ' . $u->cognoms,
                    'email'    => $u->correu,
                    'telefono' => $u->telefon ?? '-',
                    'ofertas'  => (int) $u->ofertes_count,
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

    // POST /api/clientes — crea un nuevo cliente
    public function store(Request $request)
    {
        // Validar los campos obligatorios
        $request->validate([
            'empresa'  => 'required|string',
            'cif'      => 'required|string',
            'contacto' => 'required|string',
            'email'    => 'required|email',
        ]);

        // Separar contacto en nom y cognoms (ej: "Juan García" → nom: Juan, cognoms: García)
        $partes = explode(' ', $request->contacto, 2);

        $usuario = new Usuarios();
        $usuario->empresa  = $request->empresa;
        $usuario->cif      = $request->cif;
        $usuario->nom      = $partes[0];
        $usuario->cognoms  = $partes[1] ?? '';
        $usuario->correu   = $request->email;
        $usuario->telefon  = $request->telefono ?? null;
        $usuario->actiu    = true;
        $usuario->rol_id   = 3; // siempre cliente
        $usuario->password = bcrypt('password123'); // contraseña temporal
        $usuario->save();

        return response()->json([
            'id'       => $usuario->id,
            'empresa'  => $usuario->empresa,
            'cif'      => $usuario->cif,
            'contacto' => $usuario->nom . ' ' . $usuario->cognoms,
            'email'    => $usuario->correu,
            'telefono' => $usuario->telefon ?? '-',
            'ofertas'  => 0,
            'activo'   => true,
        ], 201);
    }

    // PUT /api/clientes/{id} — edita un cliente existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'empresa'  => 'required|string',
            'cif'      => 'required|string',
            'contacto' => 'required|string',
            'email'    => 'required|email',
        ]);

        $usuario = Usuarios::findOrFail($id);

        $partes = explode(' ', $request->contacto, 2);

        $usuario->empresa = $request->empresa;
        $usuario->cif     = $request->cif;
        $usuario->nom     = $partes[0];
        $usuario->cognoms = $partes[1] ?? '';
        $usuario->correu  = $request->email;
        $usuario->telefon = $request->telefono ?? null;
        $usuario->save();

        return response()->json([
            'id'       => $usuario->id,
            'empresa'  => $usuario->empresa,
            'cif'      => $usuario->cif,
            'contacto' => $usuario->nom . ' ' . $usuario->cognoms,
            'email'    => $usuario->correu,
            'telefono' => $usuario->telefon ?? '-',
            'ofertas'  => 0,
            'activo'   => (bool) $usuario->actiu,
        ]);
    }


    // DELETE /api/clientes/{id} — elimina o desactiva según si tiene ofertas
    public function destroy($id)
    {
        $usuario = Usuarios::withCount('ofertes')->findOrFail($id);

        // Si tiene ofertas relacionadas, no se puede borrar — se desactiva
        if ($usuario->ofertes_count > 0) {
            $usuario->actiu = false;
            $usuario->save();

            return response()->json([
                'mensaje' => 'El cliente tiene ofertas asociadas y ha sido desactivado en lugar de eliminado.',
                'desactivado' => true,
            ]);
        }

        // Si no tiene ofertas, se elimina directamente
        $usuario->delete();

        return response()->json([
            'mensaje' => 'Cliente eliminado correctamente.',
            'desactivado' => false,
        ]);
    }
}
