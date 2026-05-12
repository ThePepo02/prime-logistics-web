<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupersetController extends Controller
{
    // Esta es la url del super set que tenemos en el Docker Compose que se ve en el contenedor
    private $supersetUrl = 'http://localhost:8088';

    private $username = 'admin';
    private $password = 'admin';

    public function gestToken(Request $request)
    {

        try {
            // Paso 1 - Hacer login en Superset para obtener  el access token
            $loginResponse = HTTP::post("{$this->supersetUrl}/api/v1/security/login", [
                'username' => $this->username,
                'password' => $this->password,
                'provider' => 'db',
                'refresh' => true,
            ]);

            if (!$loginResponse->successful()) {
                return response()->json(['error' => 'No se pudo conectar con Superset'], 500);
            }

            $accessToken = $loginResponse->json('access_token');


            // Paso 2 - Obtener el CSRF token de Superset usando el access token
            $csrfResponse = Http::withToken($accessToken)
                ->get("{$this->supersetUrl}/api/v1/security/csrf_token/");

            $csrfToken = $csrfResponse->json('result');


            // Paso 3 - Pedir el guest token con el ID del dashboard
            // El dashboard_id se obtiene en superset -> Dashboard -> ... -> Embed dashboard
            $dashboardId = $request->query('dashboard_id', '');

            $guestTokenResponse = Http::withToken($accessToken)
                ->withHeaders(['X-CSRFToken' => $csrfToken])
                ->post("{$this->supersetUrl}/api/v1/security/guest_token", [
                    'user' => [
                        'username' => 'guest_user',
                        'first_name' => 'Guest',
                        'last_name' => 'User',
                    ],
                    'resources' => [
                        [
                            'type' => 'dashboard',
                            'id' => $dashboardId,
                        ]
                    ],
                    'rls' => [], //Row level security rules, si las tienes configuradas en Superset
                ]);

            if (!$guestTokenResponse->successful()) {
                return response()->json(['error' => 'No se pudo obtener el guest token'], 500);
            }

            return response()->json([
                'token' => $guestTokenResponse->json('token')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
