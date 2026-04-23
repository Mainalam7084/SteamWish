<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController
{
    private const FEATURED_URL = 'https://store.steampowered.com/api/featuredcategories?cc=es&l=en';

    /**
     * Muestra la página principal.
     *
     * @return View
     */
    public function index(): View
    {
        return view('pages.home');
    }

    /**
     * Devuelve datos destacados de Steam para la home.
     *
     * @return JsonResponse
     */
    public function homeData(): JsonResponse
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

    /**
     * Muestra la página "About".
     *
     * @return View
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Muestra la página de contacto.
     *
     * @return View
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Valida y procesa el formulario de contacto.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function contactStore(Request $request): RedirectResponse
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

    /**
     * Muestra el panel de usuario para usuarios autenticados.
     *
     * @return View
     */
    public function dashboard(): View
    {
        return view('pages.dashboard');
    }


    /**
     * Normaliza los items recibidos de Steam en un array plano y usable.
     *
     * @param array $items
     * @param int $limit
     * @return array
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

    /**
     * Devuelve una respuesta vacía para la API cuando Steam no responde.
     *
     * @return array
     */
    private function emptyPayload(): array
    {
        return ['mostPlayed' => [], 'trending' => [], 'upcoming' => []];
    }
}
