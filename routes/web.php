<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

// Pagina Principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Paginas de Juego
Route::get('/search', [GameController::class, 'index'])->name('search');
Route::get('/game', [GameController::class, 'show'])->name('game');

// API
Route::get('/api/search', [SearchController::class, 'index']);

// Autenticación
// Route::get('/login', [AuthController::class, 'login'])->name('login');
// Route::get('/auth/steam', [AuthController::class, 'redirectToSteam'])->name('auth.steam');
// Route::get('/auth/steam/callback', [AuthController::class, 'handleSteamCallback'])->name('auth.steam.callback');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//ruta para get
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

//ruta del formulario 
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

//ruta para About Us
Route::get('/about', [AboutController::class, 'about'])->name('pages.about');