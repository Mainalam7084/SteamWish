<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Pagina Principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Paginas de Juego
Route::get('/search', [GameController::class, 'index'])->name('search');
Route::get('/game', [GameController::class, 'show'])->name('game');

// API
Route::get('/api/search', [SearchController::class, 'index']);

// Autenticación
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/steam', [AuthController::class, 'redirectToSteam'])->name('auth.steam');
Route::get('/auth/steam/callback', [AuthController::class, 'handleSteamCallback'])->name('auth.steam.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ruta para get y post de contacto
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact');

// ruta para About Us
Route::get('/about', [AboutController::class, 'about'])->name('about');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard'); // We will create this view
    })->name('dashboard');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
