<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController
{
    private const FEATURED_URL = 'https://store.steampowered.com/api/featuredcategories?cc=es&l=en';

    // ==========================================
    // Home Page — returns view instantly
    // ==========================================

    public function index()
    {
        return view('pages.home');
    }

    // ==========================================
    // API Endpoint — fetches Steam data
    // GET /api/home-data
    // ==========================================

    public function homeData()
    {
        try {
            $response = Http::timeout(10)->get(self::FEATURED_URL);

            if ($response->failed()) {
                return response()->json($this->emptyPayload(), 200);
            }

            $data = $response->json();

            return response()->json([
                'mostPlayed' => $this->parseItems($data['top_sellers']['items'] ?? [], 8),
                'trending'   => $this->parseItems($data['specials']['items']    ?? [], 8),
                'upcoming'   => $this->parseItems($data['coming_soon']['items'] ?? [], 12),
            ]);
        } catch (\Throwable $e) {
            Log::error('HomeController::homeData failed', ['error' => $e->getMessage()]);
            return response()->json($this->emptyPayload(), 200);
        }
    }

    // ==========================================
    // About Section
    // ==========================================

    public function about()
    {
        return view('pages.about');
    }

    // ==========================================
    // Contact Section
    // ==========================================

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:100',
            'email'  => 'required|email|max:150',
            'mensaje'=> 'required|string|min:10|max:200',
        ];

        $message = [
            'nombre.required'  => 'Por favor, dinos tu nombre.',
            'nombre.min'       => 'El nombre debe tener al menos 3 letras.',
            'nombre.max'       => 'El nombre es demasiado largo.',
            'email.required'   => 'El correo es obligatorio',
            'email.email'      => 'Debes introducir un correo válido (ej: hola@web.com).',
            'mensaje.required' => 'No olvides escribir tu mensaje.',
            'mensaje.min'      => 'El mensaje es muy corto',
            'mensaje.max'      => 'El mesaje debe de tener máximo 200 caracteres',
        ];

        $request->validate($rules, $message);

        return back()->with('success', '¡Gracias por tu mensaje! Te contactaremos pronto.');
    }

    // ==========================================
    // Dashboard (Protected Route)
    // ==========================================
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    // ==========================================
    // Private Helpers
    // ==========================================

    /**
     * Normalise a Steam featured-categories item into a clean flat array.
     */
    private function parseItems(array $items, int $limit): array
    {
        $result = [];

        foreach (array_slice($items, 0, $limit) as $item) {
            $appid    = $item['id'] ?? null;
            $name     = $item['name'] ?? '';
            $isFree   = (bool) ($item['is_free_game'] ?? false);
            $final    = $item['final_price']    ?? 0;
            $original = $item['original_price'] ?? 0;
            $discount = $item['discount_percent'] ?? 0;

            if ($isFree) {
                $priceStr = 'Free';
            } elseif ($final > 0) {
                $priceStr = number_format($final / 100, 2) . '€';
            } else {
                $priceStr = 'Free';
            }

            $result[] = [
                'appid'    => $appid,
                'name'     => $name,
                'image'    => $item['large_capsule_image'] ?? $item['header_image'] ?? null,
                'price'    => $priceStr,
                'discount' => $discount,
                'is_free'  => $isFree,
            ];
        }

        return $result;
    }

    private function emptyPayload(): array
    {
        return ['mostPlayed' => [], 'trending' => [], 'upcoming' => []];
    }
}
