<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Client;
use App\Models\TrackingStep;
use App\Models\Notificacio;
use App\Models\TipusTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Obtener todos los datos necesarios para el dashboard
     */
    public function getDashboardData(Request $request)
    {
        try {
            // Datos de KPI
            $kpiData = $this->getKPIData();
            
            // Datos de actividad semanal
            $weeklyActivity = $this->getWeeklyActivity();
            
            // Datos de ofertas recientes
            $recentOffers = $this->getRecentOffers($request);
            
            // Datos del usuario actual
            $userData = $this->getUserData();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'kpi' => $kpiData,
                    'weekly_activity' => $weeklyActivity,
                    'offers' => $recentOffers,
                    'user' => $userData
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener datos KPI del dashboard
     */
    private function getKPIData()
    {
        // Fechas para comparativas
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $currentWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        
        // Total de ofertas (mes actual vs mes anterior)
        $totalOfertasCurrent = Oferta::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
            
        $totalOfertasLast = Oferta::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
            
        $tasaOfertasTrend = $totalOfertasLast > 0 
            ? round((($totalOfertasCurrent - $totalOfertasLast) / $totalOfertasLast) * 100, 1)
            : 0;
        
        // Ofertas aceptadas
        $aceptadasCurrent = Oferta::where('estat', 'ACEPTADA')
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
            
        $aceptadasLast = Oferta::where('estat', 'ACEPTADA')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
            
        $aceptadasTrend = $aceptadasLast > 0 
            ? round((($aceptadasCurrent - $aceptadasLast) / $aceptadasLast) * 100, 1)
            : ($aceptadasCurrent > 0 ? 100 : 0);
        
        // Ofertas en tránsito (esta semana vs semana pasada)
        $enFavorCurrent = Oferta::where('estat', 'EN_TRANSIT')
            ->whereBetween('created_at', [$currentWeek, Carbon::now()])
            ->count();
            
        $enFavorLast = Oferta::where('estat', 'EN_TRANSIT')
            ->whereBetween('created_at', [$lastWeek, $currentWeek])
            ->count();
            
        $enFavorTrend = $enFavorLast > 0 
            ? round((($enFavorCurrent - $enFavorLast) / $enFavorLast) * 100, 1)
            : ($enFavorCurrent > 0 ? 100 : 0);
        
        // Incidentes (tracking con issues)
        $incidenciasCurrent = TrackingStep::where('estat', 'INCIDENCIA')
            ->whereBetween('created_at', [$currentWeek, Carbon::now()])
            ->count();
            
        $incidenciasLast = TrackingStep::where('estat', 'INCIDENCIA')
            ->whereBetween('created_at', [$lastWeek, $currentWeek])
            ->count();
            
        $incidenciasTrend = $incidenciasLast > 0 
            ? round((($incidenciasCurrent - $incidenciasLast) / $incidenciasLast) * 100, 1)
            : ($incidenciasCurrent > 0 ? 100 : 0);
        
        // Porcentaje de aceptación global
        $totalOfertas = Oferta::count();
        $totalAceptadas = Oferta::where('estat', 'ACEPTADA')->count();
        $porcentajeAceptacion = $totalOfertas > 0 
            ? round(($totalAceptadas / $totalOfertas) * 100, 1)
            : 0;
        
        // Ofertas activas
        $activas = Oferta::whereIn('estat', ['PENDIENTE', 'EN_TRANSIT', 'ACEPTADA'])->count();
        
        return [
            'totalOfertas' => $totalOfertasCurrent,
            'tasaOfertasTrend' => $tasaOfertasTrend,
            'aceptadas' => $aceptadasCurrent,
            'porcentajeAceptacion' => $porcentajeAceptacion,
            'aceptadasTrend' => $aceptadasTrend,
            'enFavor' => $enFavorCurrent,
            'enFavorTrend' => $enFavorTrend,
            'incidencias' => $incidenciasCurrent,
            'incidenciasTrend' => $incidenciasTrend,
            'activas' => $activas
        ];
    }
    
    /**
     * Obtener actividad semanal para gráficos
     */
    private function getWeeklyActivity()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $daysOfWeek = [];
        
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayName = $this->getDayNameSpanish($day->dayOfWeek);
            
            // Ofertas enviadas por día
            $enviadas = Oferta::whereDate('created_at', $day)->count();
            
            // Ofertas aceptadas por día
            $aceptadas = Oferta::where('estat', 'ACEPTADA')
                ->whereDate('updated_at', $day)
                ->count();
            
            // Incidentes por día
            $incidentes = TrackingStep::where('estat', 'INCIDENCIA')
                ->whereDate('created_at', $day)
                ->count();
            
            $daysOfWeek[] = [
                'day' => $dayName,
                'enviadas' => $enviadas,
                'aceptadas' => $aceptadas,
                'incidentes' => $incidentes
            ];
        }
        
        return $daysOfWeek;
    }
    
    /**
     * Obtener ofertas recientes
     */
    private function getRecentOffers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $query = Oferta::with(['client', 'tipusTransport', 'tipusFlux']);
        
        // Filtro de búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('client', function($q2) use ($search) {
                      $q2->where('nom', 'LIKE', "%{$search}%")
                         ->orWhere('cognoms', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filtro de estado
        if ($status) {
            $query->where('estat', $status);
        }
        
        $offers = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Transformar datos para el frontend
        return [
            'data' => $offers->items(),
            'total' => $offers->total(),
            'current_page' => $offers->currentPage(),
            'last_page' => $offers->lastPage(),
            'per_page' => $offers->perPage()
        ];
    }
    
    /**
     * Obtener datos del usuario actual
     */
    private function getUserData()
    {
        $user = auth()->user();
        
        // Obtener rol del usuario
        $rol = $user->rol ? $user->rol->nom : 'USUARIO';
        
        return [
            'nombre' => $user->name ?? $user->nom ?? 'Usuario',
            'rol' => strtoupper($rol),
            'email' => $user->email,
            'avatar' => $user->avatar ?? null
        ];
    }
    
    /**
     * Exportar datos del dashboard
     */
    public function exportDashboardData(Request $request)
    {
        try {
            $type = $request->get('type', 'offers');
            $format = $request->get('format', 'csv');
            
            switch ($type) {
                case 'offers':
                    $data = $this->getExportOffersData($request);
                    break;
                case 'kpi':
                    $data = $this->getKPIData();
                    break;
                case 'weekly':
                    $data = $this->getWeeklyActivity();
                    break;
                default:
                    $data = $this->getKPIData();
            }
            
            if ($format === 'csv') {
                return $this->exportToCSV($data, "dashboard_{$type}_" . date('Y-m-d'));
            } else {
                return response()->json($data);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar los datos'
            ], 500);
        }
    }
    
    /**
     * Obtener datos de ofertas para exportar
     */
    private function getExportOffersData(Request $request)
    {
        $query = Oferta::with(['client', 'tipusTransport', 'tipusFlux']);
        
        if ($request->has('status') && $request->status) {
            $query->where('estat', $request->status);
        }
        
        $offers = $query->limit(1000)->get();
        
        return $offers->map(function($offer) {
            return [
                'ID Oferta' => $offer->id,
                'Cliente' => $offer->client ? $offer->client->nom . ' ' . $offer->client->cognoms : 'N/A',
                'Empresa' => $offer->empresa ?? 'N/A',
                'Modo' => $offer->tipusTransport ? $offer->tipusTransport->nom : 'N/A',
                'Ruta' => $this->formatRoute($offer),
                'Distancia' => $offer->distancia ?? '0 Km',
                'Estado' => $this->getStatusSpanish($offer->estat),
                'Fecha Creación' => $offer->created_at->format('d/m/Y'),
                'Total' => $offer->total ?? 0
            ];
        });
    }
    
    /**
     * Exportar a CSV
     */
    private function exportToCSV($data, $filename)
    {
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Si es colección de objetos, obtener las cabeceras del primer elemento
            if (is_array($data) && count($data) > 0 && is_array($data[0])) {
                fputcsv($file, array_keys($data[0]), ';');
                
                foreach ($data as $row) {
                    fputcsv($file, $row, ';');
                }
            } elseif (is_array($data)) {
                // Para datos KPI que son un solo array
                fputcsv($file, array_keys($data), ';');
                fputcsv($file, array_values($data), ';');
            }
            
            fclose($file);
        };
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Obtener notificaciones del dashboard
     */
    public function getNotifications()
    {
        try {
            $user = auth()->user();
            
            $notifications = Notificacio::where('usuari_id', $user->id)
                ->orWhere('tipus', 'GLOBAL')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            
            $unreadCount = $notifications->where('llegit', false)->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications,
                    'unread_count' => $unreadCount
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar notificaciones'
            ], 500);
        }
    }
    
    /**
     * Marcar notificación como leída
     */
    public function markNotificationAsRead($id)
    {
        try {
            $notification = Notificacio::findOrFail($id);
            $notification->llegit = true;
            $notification->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar notificación'
            ], 500);
        }
    }
    
    /**
     * Métodos de utilidad
     */
    private function getDayNameSpanish($dayNumber)
    {
        $days = [
            1 => 'Lun',
            2 => 'Mar',
            3 => 'Mié',
            4 => 'Jue',
            5 => 'Vie',
            6 => 'Sáb',
            7 => 'Dom',
        ];
        
        return $days[$dayNumber] ?? 'N/A';
    }
    
    private function getStatusSpanish($status)
    {
        $statuses = [
            'PENDIENTE' => 'Pendiente',
            'ACEPTADA' => 'Aceptada',
            'EN_TRANSIT' => 'En Tránsito',
            'COMPLETADA' => 'Completada',
            'RECHAZADA' => 'Rechazada',
            'CANCELADA' => 'Cancelada'
        ];
        
        return $statuses[$status] ?? $status;
    }
    
    private function formatRoute($offer)
    {
        $origin = $offer->origen ?? 'N/A';
        $destination = $offer->desti ?? 'N/A';
        
        return "{$origin}-{$destination}";
    }
    
    /**
     * Obtener estadísticas adicionales para el dashboard
     */
    public function getAdvancedStats()
    {
        try {
            // Estadísticas por modo de transporte
            $statsByTransport = TipusTransport::withCount('ofertes')
                ->get()
                ->map(function($transport) {
                    return [
                        'modo' => $transport->nom,
                        'total' => $transport->ofertes_count
                    ];
                });
            
            // Estadísticas por cliente (top 5)
            $topClients = Client::withCount('ofertes')
                ->orderBy('ofertes_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($client) {
                    return [
                        'cliente' => $client->nom . ' ' . $client->cognoms,
                        'total_ofertas' => $client->ofertes_count
                    ];
                });
            
            // Ofertas por mes (últimos 6 meses)
            $monthlyOffers = Oferta::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function($item) {
                    $date = Carbon::createFromDate($item->year, $item->month, 1);
                    return [
                        'mes' => $date->format('M Y'),
                        'total' => $item->total
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'by_transport' => $statsByTransport,
                    'top_clients' => $topClients,
                    'monthly_offers' => $monthlyOffers
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar estadísticas avanzadas'
            ], 500);
        }
    }
}