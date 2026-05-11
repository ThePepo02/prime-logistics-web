<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Naviera;
use App\Models\PuertoAeropuerto;
use App\Models\AgenteAduanal;
use App\Models\Incoterm;

class DatosMaestrosController extends Controller
{
    // Obtener todos los datos maestros en una sola petición
    public function getDashboardData()
    {
        try {
            $data = [
                'empresas' => Empresa::where('estado', 'ACTIVA')->get(),
                'navieras' => Naviera::where('estado', 'ACTIVA')->get(),
                'puertos_aeropuertos' => PuertoAeropuerto::where('estado', 'ACTIVO')->get(),
                'agentes_aduanales' => AgenteAduanal::where('estado', 'ACTIVO')->get(),
                'incoterms' => Incoterm::where('estado', 'ACTIVO')->get(),
                'resumen' => [
                    'total_empresas' => Empresa::count(),
                    'total_navieras_activas' => Naviera::where('estado', 'ACTIVA')->count(),
                    'total_puertos_activos' => PuertoAeropuerto::where('estado', 'ACTIVO')->count(),
                    'total_agentes_activos' => AgenteAduanal::where('estado', 'ACTIVO')->count(),
                    'total_incoterms' => Incoterm::count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos maestros',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Estadísticas generales
    public function getEstadisticas()
    {
        try {
            $estadisticas = [
                'empresas' => [
                    'total' => Empresa::count(),
                    'activas' => Empresa::where('estado', 'ACTIVA')->count(),
                    'total_usuarios' => Empresa::sum('total_usuarios'),
                    'total_pedidos' => Empresa::sum('total_pedidos')
                ],
                'navieras' => [
                    'total' => Naviera::count(),
                    'maritimas' => Naviera::where('tipo', 'MARITIMO')->count(),
                    'aereas' => Naviera::where('tipo', 'AEREO')->count(),
                    'total_operaciones' => Naviera::sum('total_operaciones')
                ],
                'puertos' => [
                    'total' => PuertoAeropuerto::count(),
                    'puertos' => PuertoAeropuerto::where('tipo', 'PUERTO')->count(),
                    'aeropuertos' => PuertoAeropuerto::where('tipo', 'AEROPUERTO')->count(),
                    'total_operaciones' => PuertoAeropuerto::sum('total_operaciones')
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar estadísticas'
            ], 500);
        }
    }
}
