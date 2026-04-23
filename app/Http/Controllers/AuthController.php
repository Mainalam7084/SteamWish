<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController
{
    /**
     * Redirige al usuario al flujo de login de Steam OpenID.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectToSteam(Request $request): RedirectResponse
    {
        $params = [
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.return_to' => route('auth.steam.callback'),
            'openid.realm' => url('/'),
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        return redirect('https://steamcommunity.com/openid/login?'.http_build_query($params));
    }

    /**
     * Maneja el callback de Steam y autentica al usuario en la aplicación.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function handleSteamCallback(Request $request): RedirectResponse
    {
        if (! $request->has('openid_mode') || $request->input('openid_mode') === 'cancel') {
            return redirect('/')->with('error', 'Login cancelado.');
        }

        $params = [];
        // Convertir los parámetros recibidos de OpenID al formato que espera Steam.
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'openid_')) {
                $params['openid.'.substr($key, 7)] = $value;
            } else {
                $params[$key] = $value;
            }
        }
        $params['openid.mode'] = 'check_authentication';

        // Validar la firma con los servidores de Steam.
        $response = Http::asForm()->post('https://steamcommunity.com/openid/login', $params);

        if (preg_match('/is_valid\s*:\s*true/i', $response->body())) {
            preg_match('#^https?://steamcommunity.com/openid/id/([0-9]{17,25})#', $request->input('openid_claimed_id'), $matches);
            $steamId = $matches[1] ?? null;

            if ($steamId) {
                $apiKey = config('app.api_key');
                $profileResponse = Http::get('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/', [
                    'key' => $apiKey,
                    'steamids' => $steamId,
                ]);

                if ($profileResponse->successful()) {
                    $player = $profileResponse->json('response.players')[0] ?? null;
                    if ($player) {
                        $user = User::updateOrCreate(
                            ['steam_id' => $steamId],
                            [
                                'username' => $player['personaname'],
                                'avatar' => $player['avatarfull'],
                                'profile_url' => $player['profileurl'],
                            ]
                        );
                        Auth::login($user, true);

                        return redirect()->route('home')->with('success', 'Sesión iniciada correctamente.');
                    }
                }
            }
        }

        return redirect('/')->with('error', 'Fallo en la autenticación con Steam.');
    }

    /**
     * Cierra la sesión del usuario y limpia la sesión.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
