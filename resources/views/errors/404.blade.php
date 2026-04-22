@extends('layouts.app')

@section('title', '404 - Página No Encontrada | SteamWish')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4">
    <div class="text-center max-w-2xl">
        <div class="mb-8">
            <i data-lucide="search-x" class="w-24 h-24 mx-auto text-[#0F3A52]"></i>
        </div>
        <h1 class="text-6xl font-bold text-[#0F3A52] mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-[#5DA9D6] mb-6">Página No Encontrada</h2>
        <p class="text-lg text-gray-600 mb-8">
            Lo sentimos, la página que buscas no existe o ha sido movida. Quizás quieras buscar algo diferente o volver al inicio.
        </p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-[#0F3A52] text-white font-semibold rounded-lg hover:bg-[#1a4a6e] transition-colors">
                <i data-lucide="home" class="inline w-4 h-4 mr-2"></i>
                Volver al Inicio
            </a>
            <a href="{{ url()->previous() }}" class="px-6 py-3 border-2 border-[#0F3A52] text-[#0F3A52] font-semibold rounded-lg hover:bg-[#0F3A52] hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="inline w-4 h-4 mr-2"></i>
                Volver Atrás
            </a>
        </div>
    </div>
</div>

@endsection