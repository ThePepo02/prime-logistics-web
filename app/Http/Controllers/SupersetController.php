<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupersetController extends Controller
{
    // Esta es la url del super set que tenemos en el Docker Compose que se ve en el contenedor
    private $supersetUrl = 'http://127.0.0.1:8088';
    private $username    = 'admin';
    private $password    = 'admin';

    public function guestToken(Request $request)
    {
        try {
            // Paso 1 — Login en Superset
            $loginResponse = Http::post("{$this->supersetUrl}/api/v1/security/login", [
                'username' => $this->username,
                'password' => $this->password,
                'provider' => 'db',
                'refresh'  => true,
            ]);

            if (! $loginResponse->successful()) {
                return response()->json(['error' => 'Login fallido', 'body' => $loginResponse->json()], 500);
            }

            $accessToken = $loginResponse->json('access_token');

            // Paso 2 — Obtener CSRF token con las cookies de sesión
            $csrfResponse = Http::withToken($accessToken)
                ->withHeaders(['Referer' => $this->supersetUrl])
                ->get("{$this->supersetUrl}/api/v1/security/csrf_token/");

            $csrfToken = $csrfResponse->json('result');
            $cookies   = $csrfResponse->cookies();

            // Paso 3 — Pedir guest token enviando el CSRF token y las cookies
            $dashboardId = $request->query('dashboard_id', '5594a17e-7668-4c28-9f67-56456b54c063');

            $guestTokenResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'X-CSRFToken' => $csrfToken,
                    'Referer'     => $this->supersetUrl,
                    'Cookie'      => collect($cookies)->map(fn($c) => $c->getName() . '=' . $c->getValue())->implode('; '),
                ])
                ->post("{$this->supersetUrl}/api/v1/security/guest_token/", [
                    'user'      => [
                        'username'   => 'admin',
                        'first_name' => 'Admin',
                        'last_name'  => 'User',
                    ],
                    'resources' => [[
                        'type' => 'dashboard',
                        'id'   => $dashboardId,
                    ]],
                    'rls'       => [],
                ]);

            if (! $guestTokenResponse->successful()) {
                return response()->json([
                    'error'  => 'No se pudo obtener el guest token',
                    'status' => $guestTokenResponse->status(),
                    'body'   => $guestTokenResponse->json(),
                ], 500);
            }

            return response()->json(['token' => $guestTokenResponse->json('token')]);

        } catch (\Exception $e) {
            \Log::error('Superset error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
