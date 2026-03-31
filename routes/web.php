<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
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
