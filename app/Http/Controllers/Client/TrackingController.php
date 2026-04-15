<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        $envio = Envio::where('oferta_id', $request->code)->orWhere('id', substr($request->code, 3))->first();
        return response()->json($envio ? ['code' => $envio->oferta_id, 'status' => $envio->estado_envio] : ['error' => 'No encontrado'], $envio ? 200 : 404);
    }
}
