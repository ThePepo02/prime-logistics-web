<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('nombre')->paginate(10);
        return response()->json(['success' => true, 'data' => $empresas]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:empresas',
            'email' => 'required|email|unique:empresas',
            'cif' => 'required|unique:empresas',
            'telefono' => 'nullable',
            'direccion' => 'nullable'
        ]);

        $empresa = Empresa::create($validated);
        return response()->json(['success' => true, 'data' => $empresa], 201);
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return response()->json(['success' => true, 'data' => $empresa]);
    }

    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Empresa eliminada']);
    }
}