<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $offers = Oferta::latest('id')->limit(12)->get();
        $notices = $offers->map(fn ($o, $i) => [
            'id' => $o->id,
            'title' => 'Oferta #' . $o->id,
            'message' => $o->comentaris ?: 'Nueva oferta',
            'code' => 'OC-' . $o->id,
            'type' => 'Pendiente',
            'typeClass' => 'sent',
            'time' => $o->data_creacio?->format('Y-m-d') ?? '-',
            'featured' => $i === 0,
            'primary' => 'Ver',
            'secondary' => $i === 0 ? 'Rechazar' : null,
        ])->toArray();

        return response()->json([
            'notices' => $notices,
            'priority' => $notices[0] ?? null,
            'stats' => ['unread' => min(3, count($notices)), 'total' => count($notices)],
        ]);
    }

    public function accept(Request $request)
    {
        Oferta::find($request->offer_id)?->update(['estat_oferta_id' => 2]);
        return response()->json(['ok' => true]);
    }

    public function reject(Request $request)
    {
        Oferta::find($request->offer_id)?->update(['estat_oferta_id' => 3]);
        return response()->json(['ok' => true]);
    }
}
