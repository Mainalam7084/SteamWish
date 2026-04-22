<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Ruta para Pagina Principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas para Paginas de Juego
Route::get('/search', [GameController::class, 'index'])->name('search');
Route::get('/game', [GameController::class, 'show'])->name('game');

// Rutas de API
Route::get('/api/search', [SearchController::class, 'index']);
Route::get('/api/home-data', [HomeController::class, 'homeData'])->name('api.home-data');
Route::get('/api/wishlist-ids', [WishlistController::class, 'ids'])->name('api.wishlist-ids');
Route::get('/api/wishlist-preview', [WishlistController::class, 'preview'])->name('api.wishlist-preview');

// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/steam', [AuthController::class, 'redirectToSteam'])->name('auth.steam');
Route::get('/auth/steam/callback', [AuthController::class, 'handleSteamCallback'])->name('auth.steam.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para get y post de contacto
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->name('contact.store');

// Ruta para About Us
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Routas protegidas -> Rutas para perfil de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    //Rutas de wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Error 403 - Acceso denegado
Route::get('/test-403', function () {
    abort(403, 'Acceso denegado');
});
// Error 419 - Sesión expirada
Route::get('/test-419', function () {
    abort(419, 'Sesión expirada');
});

// Error 429 - Demasiadas solicitudes
Route::get('/test-429', function () {
    abort(429, 'Demasiadas solicitudes');
});

// Error 500 - Error del servidor
Route::get('/test-500', function () {
    abort(500, 'Error del servidor');
});

// Error 503 - Servicio no disponible
Route::get('/test-503', function () {
    abort(503, 'Servicio no disponible');
});
});
