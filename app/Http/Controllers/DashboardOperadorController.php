<?php
namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\JsonResponse;

class DashboardOperadorController extends Controller
{
    public function stats(): JsonResponse
    {
        $total       = Oferta::count();
        $pendents    = Oferta::where('estat_oferta_id', 1)->count();
        $acceptades  = Oferta::where('estat_oferta_id', 2)->count();
        $incidencies = Oferta::where('estat_oferta_id', 4)->count();

        return response()->json([
            'pendents'    => $pendents,
            'acceptades'  => $acceptades,
            'en_curs'     => $total,
            'incidencies' => $incidencies,
        ]);
    }

    public function ultimes(): JsonResponse
    {
        $ofertes = Oferta::with('client')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($oferta) {
                $client  = $oferta->client;
                $nom     = $client ? $client->nom . ' ' . $client->cognoms : 'Sin cliente';
                $empresa = $client ? ($client->empresa ?? '-') : '-';
                $data    = $oferta->data_creacio;

                $dataFormatada = (! $data || str_starts_with($data, '0001'))
                    ? 'Sin fecha'
                    : substr($data, 0, 10);

                $mode = match ((int) $oferta->tipus_transport_id) {
                    1 => 'Marítimo', 2 => 'Aéreo', 3 => 'Terrestre',     default => 'Multimodal',
                };

                $status = match ((int) $oferta->estat_oferta_id) {
                    1 => 'ENVIADA', 2 => 'ACEPTADA', 3 => 'BORRADOR',     default => 'PENDIENTE',
                };

                return [
                    'id'      => 'OC-' . str_pad($oferta->id, 4, '0', STR_PAD_LEFT),
                    'client'  => $nom,
                    'empresa' => $empresa,
                    'mode'    => $mode,
                    'route'   => $oferta->comentaris ?? '-',
                    'sent'    => $dataFormatada,
                    'status'  => $status,
                ];
            });

        return response()->json($ofertes);
    }

    public function alertes(): JsonResponse
    {
        $alertes = Oferta::with('client')
            ->whereIn('estat_oferta_id', [3, 4])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($oferta) {
                $issue = match ((int) $oferta->estat_oferta_id) {
                    3       => 'Oferta rebutjada',
                    4       => 'Incidència detectada',
                    default => 'Requereix atenció',
                };
                return [
                    'id'    => 'OC-' . str_pad($oferta->id, 4, '0', STR_PAD_LEFT),
                    'issue' => $issue,
                ];
            });

        return response()->json($alertes);
    }

    public function distribucio(): JsonResponse
    {
        $maritim    = Oferta::where('tipus_transport_id', 1)->count();
        $aeri       = Oferta::where('tipus_transport_id', 2)->count();
        $terrestre  = Oferta::where('tipus_transport_id', 3)->count();
        $multimodal = Oferta::where('tipus_transport_id', 4)->count();
        $total      = $maritim + $aeri + $terrestre + $multimodal;

        $pct = fn($n) => $total > 0 ? round($n * 100 / $total) : 0;

        return response()->json([
            ['type' => 'Marítimo', 'count' => $maritim, 'percent' => $pct($maritim), 'color' => 'bg-blue-600'],
            ['type' => 'Aéreo', 'count' => $aeri, 'percent' => $pct($aeri), 'color' => 'bg-blue-400'],
            ['type' => 'Terrestre', 'count' => $terrestre, 'percent' => $pct($terrestre), 'color' => 'bg-orange-400'],
            ['type' => 'Multimodal', 'count' => $multimodal, 'percent' => $pct($multimodal), 'color' => 'bg-gray-300'],
        ]);
    }

}
