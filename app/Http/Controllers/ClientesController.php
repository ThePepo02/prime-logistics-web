<?php
namespace App\Http\Controllers;

use App\Classes\Utilitat;
use App\Models\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    // GET /api/clientes — devuelve todos los clientes (rol_id = 3) con sus ofertas contadas
    // withCount('ofertes') añade el campo ofertes_count en una sola query SQL
    public function index()
    {
        try {
            $clientes = Usuarios::withCount('ofertes')
                ->where('rol_id', 3)
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

        } catch (\Exception $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);
        }
    }

    // POST /api/clientes — crea un nuevo cliente en la BD
    // Lo llama ClientesComponent.vue al pulsar "Crear cliente" en el modal
    public function store(Request $request)
    {

        try {
            // Validamos los campos obligatorios
            $request->validate([
                'empresa'  => 'required|string',
                'cif'      => 'required|string|unique:usuaris,cif',
                'contacto' => 'required|string',
                'email'    => 'required|email',
            ], [
                'cif.unique'        => 'El CIF ya está registrado.',
                'empresa.required'  => 'La empresa es obligatoria.',
                'contacto.required' => 'El contacto es obligatorio.',
                'email.required'    => 'El email es obligatorio.',
                'email.email'       => 'El email no es válido.',
            ]);

            // Separamos el contacto en nom y cognoms (ej: "Juan García" → nom: Juan, cognoms: García)
            $partes = explode(' ', $request->contacto, 2);

            $usuario           = new Usuarios();
            $usuario->empresa  = $request->empresa;
            $usuario->cif      = $request->cif;
            $usuario->nom      = $partes[0];
            $usuario->cognoms  = $partes[1] ?? '';
            $usuario->correu   = $request->email;
            $usuario->telefon  = $request->telefono ?? null;
            $usuario->actiu    = true;
            $usuario->rol_id   = 3;                     // siempre cliente
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

        } catch (QueryException $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);
        }
    }

    // GET /api/clientes/{id} — devuelve un cliente por ID
    public function show($id)
    {

        try {
            $u = Usuarios::findOrFail($id);
            return response()->json([
                'id'      => $u->id,
                'nom'     => $u->nom,
                'cognoms' => $u->cognoms,
                'empresa' => $u->empresa,
                'cif'     => $u->cif,
                'correu'  => $u->correu,
                'telefon' => $u->telefon,
                'actiu'   => (bool) $u->actiu,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);
        }
    }

    // PUT /api/clientes/{id} — edita los datos de un cliente existente
    // Solo actualiza los campos que llegan — los que no llegan se quedan igual
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuarios::findOrFail($id);

            $usuario->nom     = $request->nom ?? $usuario->nom;
            $usuario->cognoms = $request->cognoms ?? $usuario->cognoms;
            $usuario->empresa = $request->empresa ?? $usuario->empresa;
            $usuario->cif     = $request->cif ?? $usuario->cif;
            $usuario->correu  = $request->correu ?? $usuario->correu;
            $usuario->telefon = $request->telefon ?? $usuario->telefon;
            $usuario->save();

            return response()->json(['ok' => true, 'cliente' => $usuario]);

        } catch (\Exception $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);
        }
    }

    // PUT /api/clientes/{id}/estado — activa o desactiva un cliente
    // El ! invierte el valor actual: si era true pasa a false y viceversa
    public function toggleEstado($id)
    {

        try {
            $usuario        = Usuarios::findOrFail($id);
            $usuario->actiu = ! $usuario->actiu;
            $usuario->save();

            return response()->json(['activo' => (bool) $usuario->actiu]);

        } catch (\Exception $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);
        }
    }

    // DELETE /api/clientes/{id} — elimina si no tiene ofertas, desactiva si tiene
    // No podemos borrar un cliente con ofertas porque SQL Server lanzaría un error
    // de clave foránea (FK_ofertes_usuaris) — la BD protege la integridad de los datos
    public function destroy($id)
    {

        try {
            $usuario = Usuarios::withCount('ofertes')->findOrFail($id);
            if ($usuario->ofertes_count > 0) {
                // Tiene ofertas — desactivamos en vez de borrar
                $usuario->actiu = false;
                $usuario->save();

                return response()->json([
                    'ok'          => true,
                    'desactivado' => true,
                    'mensaje'     => 'El cliente tiene ofertas asociadas y ha sido desactivado.',
                ]);
            }

            // Sin ofertas — eliminamos definitivamente

            $usuario->delete();

            return response()->json([
                'ok'        => true,
                'eliminado' => true,
                'mensaje'   => 'Cliente eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok'    => false,
                'error' => Utilitat::errorMessage($e),
            ], 500);

        }

    }
}
