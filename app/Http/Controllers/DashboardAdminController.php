<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfertaResource;
use App\Http\Resources\UsuariosResource;
use App\Models\Oferta;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $oferta = Oferta::all();
        $client = Usuarios::all();

        return OfertaResource::collection($oferta) && UsuariosResource::collection($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Oferta $oferta, Usuarios $client)
    {
        $oferta->id = $request->id;
        $client->nom = $request->nom;
    }
}