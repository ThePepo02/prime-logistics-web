<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correu' => 'required|email',
            'contrasenya' => 'required',
        ]);

        $user = Usuarios::where('correu', $request->correu)->first();

        if (!$user || !Hash::check($request->contrasenya, $user->contrasenya)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Aquí puedes iniciar sesión al usuario, por ejemplo usando Auth::login($user);
        // Luego redirige al dashboard o a la página que desees
        return response()->json([
            'token' => $token,
            'user' => $user ->load('rol') // Carga la relación con el rol
        ]);
    }

    public function logout (Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        // Aquí puedes cerrar sesión al usuario, por ejemplo usando Auth::logout();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
