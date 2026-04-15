<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    // Devuelve todos los usuarios con rol_id = 3 (clientes)
    public function index()
    {
        $clientes = Clientes::clientes()->get();
        return response()->json($clientes);
    }
}
