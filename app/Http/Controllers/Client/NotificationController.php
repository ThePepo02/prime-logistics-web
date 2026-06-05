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

    private function getTrackingId($entitat_tipus, $entitat_id, $tipus)
    {
        // Si es de tipo envio, usar entitat_id como tracking_id
        if (!empty($tipus)) {
            $tipus_check = strtolower(trim($tipus));
            if ($tipus_check === 'envio' || strpos($tipus_check, 'envio') !== false) {
                return $entitat_id;
            }
        }
        
        if (!empty($entitat_tipus) && $entitat_id) {
            if ($entitat_tipus === 'TrackingStep') {
                $step = TrackingStep::find($entitat_id);
                return $step?->oferta_id;
            } elseif ($entitat_tipus === 'Oferta') {
                return $entitat_id;
            }
        }
        
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
