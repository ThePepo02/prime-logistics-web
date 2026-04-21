<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsuariosResource;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuarios::all();
        return UsuariosResource::collection($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = new Usuarios();
        $usuarios->correu = $request->correu;
        $usuarios->contrasenya = $request->contrasenya;
        $usuarios->nom = $request->nom;
        $usuarios->cognoms = $request->cognoms;
        $usuarios->empresa = $request->empresa;

        $usuarios->save();
        return new UsuariosResource($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuarios->correu = $request->correu;
        $usuarios->contrasenya = $request->contrasenya;
        $usuarios->nom = $request->nom;
        $usuarios->cognoms = $request->cognoms;
        $usuarios->empresa = $request->empresa;

        $usuarios->save();
        return new UsuariosResource($usuarios);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuarios $usuarios)
    {
        $usuarios = Usuarios::find($usuarios->id);
        return new UsuariosResource($usuarios);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuarios $usuarios)
    {
        $usuarios->contrasenya = $request->contrasenya;
        $usuarios->nom = $request->nom;
        $usuarios->cognoms = $request->cognoms;
        $usuarios->empresa = $request->empresa;

        $usuarios->save();
        return new UsuariosResource($usuarios);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuarios $usuarios)
    {
        $usuarios = Usuarios::find($usuarios->id);
        $usuarios->delete();
        return new UsuariosResource($usuarios);
    }
}
