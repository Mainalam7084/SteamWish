@extends('layouts.app')

@section('title', '429 - Demasiadas Solicitudes | SteamWish')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4">
    <div class="text-center max-w-2xl">
        <div class="mb-8">
            <i data-lucide="timer-off" class="w-24 h-24 mx-auto text-[#0F3A52]"></i>
        </div>
        <h1 class="text-6xl font-bold text-[#0F3A52] mb-4">429</h1>
        <h2 class="text-2xl font-semibold text-[#5DA9D6] mb-6">Demasiadas Solicitudes</h2>
        <p class="text-lg text-gray-600 mb-8">
            Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera un momento y vuelve a intentarlo.
        </p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-[#0F3A52] text-white font-semibold rounded-lg hover:bg-[#1a4a6e] transition-colors">
                <i data-lucide="home" class="inline w-4 h-4 mr-2"></i>
                Volver al Inicio
            </a>
            <button onclick="location.reload()" class="px-6 py-3 border-2 border-[#0F3A52] text-[#0F3A52] font-semibold rounded-lg hover:bg-[#0F3A52] hover:text-white transition-colors">
                <i data-lucide="refresh-cw" class="inline w-4 h-4 mr-2"></i>
                Reintentar
            </button>
        </div>
    </div>
</div>
@endsection