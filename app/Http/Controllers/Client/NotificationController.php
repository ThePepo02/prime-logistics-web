<?php


namespace App\Http\Controllers\Client;


use App\Http\Controllers\Controller;
use App\Models\Notificacio;
use App\Models\TrackingStep;
use App\Models\Oferta;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = Notificacio::latest('data_creacio')->limit(12)->get();
            $notices = $notifications->map(fn ($n, $i) => [
                'id' => $n->id,
                'title' => $n->titol,
                'message' => $n->missatge,
                'code' => 'NOT-' . $n->id,
                'type' => $n->tipus,
                'typeClass' => $n->llegida ? 'read' : 'unread',
                'llegida' => (bool) $n->llegida,
                'time' => $n->data_creacio ? $n->data_creacio->format('Y-m-d') : '-',
                'featured' => $i === 0,
                'primary' => $n->llegida ? null : 'Ver',
                'secondary' => null,
                'entitat_tipus' => $n->entitat_tipus,
                'entitat_id' => $n->entitat_id,
                'offer_code' => $this->extractOfferCode($n->titol, $n->missatge),
                'tracking_id' => $this->getTrackingId($n->entitat_tipus, $n->entitat_id, $n->tipus),
            ])->toArray();


            $unreadCount = Notificacio::where('llegida', false)->count();


            return response()->json([
                'notices' => $notices,
                'priority' => $notices[0] ?? null,
                'stats' => [
                    'unread' => $unreadCount,
                    'total' => Notificacio::count(),
                    'pending_action' => $unreadCount,
                    'resolved_month' => Notificacio::where('llegida', true)->count()
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading notifications: ' . $e->getMessage());
            return response()->json([
                'notices' => [],
                'priority' => null,
                'stats' => [
                    'unread' => 0,
                    'total' => 0,
                    'pending_action' => 0,
                    'resolved_month' => 0
                ],
            ]);
        }
    }


    /**
     * Extrae el código de la oferta del título o mensaje
     * Busca patrones como OC-2024-018, OC-2025-023, etc.
     */
    private function extractOfferCode($titulo, $mensaje)
    {
        $text = $titulo . ' ' . $mensaje;
        if (preg_match('/OC-\d{4}-\d{3,}/', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }


    private function getTrackingId($entitat_tipus, $entitat_id, $tipus)
    {
        // Normalizar entitat_tipus a minúsculas para comparación
        $entitat_tipus_check = strtolower(trim($entitat_tipus ?? ''));
       
        // Si es de tipo envio, retornar el ID de entidad como tracking_id
        if ($entitat_tipus_check === 'envio' && $entitat_id) {
            return $entitat_id;
        }
       
        // Si es TrackingStep, obtener el oferta_id relacionado
        if ($entitat_tipus_check === 'trackingstep' && $entitat_id) {
            $step = TrackingStep::find($entitat_id);
            return $step?->oferta_id;
        }
       
        // Las notificaciones de tipo oferta no tienen tracking_id
        return null;
    }


    public function accept(Request $request)
    {
        try {
            Notificacio::find($request->notification_id)?->update(['llegida' => true]);
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['error' => 'Error'], 500);
        }
    }


    public function reject(Request $request)
    {
        try {
            Notificacio::find($request->notification_id)?->delete();
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json(['error' => 'Error'], 500);
        }
    }
}



