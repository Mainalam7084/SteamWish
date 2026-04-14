<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    // ==========================================
    // Home Section
    // ==========================================

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.home', [
            'featuredGame' => null,
            'trendingGames' => [],
            'newReleases' => [],
            'topPlayed' => [],
        ]);
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
            'email' => 'required|email|max:150',
            'mensaje' => 'required|string|min:10|max:200',
        ];
        
        $message = [
            'nombre.required' => 'Por favor, dinos tu nombre.',
            'nombre.min' => 'El nombre debe tener al menos 3 letras.',
            'nombre.max' => 'El nombre es demasiado largo.',
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Debes introducir un correo válido (ej: hola@web.com).',
            'mensaje.required' => 'No olvides escribir tu mensaje.',
            'mensaje.min' => 'El mensaje es muy corto',
            'mensaje.max' => 'El mesaje debe de tener máximo 200 caracteres'
        ];

        $request->validate($rules, $message);

        //code to send via email (if applicable)

        //return a message
        return back()->with('success', '¡Gracias por tu mensaje! Te contactaremos pronto.');
    }
}
