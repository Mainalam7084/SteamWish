<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/game', function() {
    return view('game');
});

Route::get('/index', [IndexController::class, 'index']);

Route::get('/api/search', [SearchController::class, 'index']);

