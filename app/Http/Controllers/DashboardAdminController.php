<?php
namespace App\Services;

use App\Models\Oferta;
use Carbon\Carbon;

class DashboardService
{
    public function getKPI()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $current = Oferta::whereMonth('created_at', $currentMonth->month)->count();
        $last = Oferta::whereMonth('created_at', $lastMonth->month)->count();

        return [
            'totalOfertas' => $current,
            'trend' => $last > 0 ? round((($current - $last)/$last)*100, 1) : 0,
            'aceptadas' => Oferta::where('estat', 'ACEPTADA')->count(),
            'activas' => Oferta::whereIn('estat', ['PENDIENTE','EN_TRANSIT'])->count()
        ];
    }

    public function getWeeklyActivity()
    {
        $start = Carbon::now()->startOfWeek();
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);

            $data[] = [
                'date' => $day->format('Y-m-d'),
                'enviadas' => Oferta::whereDate('created_at', $day)->count(),
                'aceptadas' => Oferta::where('estat', 'ACEPTADA')
                    ->whereDate('updated_at', $day)->count()
            ];
        }

        return $data;
    }

    public function getRecentOffers($request)
    {
        return Oferta::with('client')
            ->latest()
            ->paginate($request->get('per_page', 10));
    }

    public function getUserData()
    {
        $user = auth()->user();

        return [
            'name' => $user->name,
            'email' => $user->email
        ];
    }
}
?>