@extends('layouts.app')

@section('title', 'SteamWish – Gaming Wishlist & Deals')

@section('content')

    {{-- ══════════════════════════════════════ --}}
    {{--  HERO SECTION                         --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="hero" class="relative border-b-4 border-black overflow-hidden bg-[#0F3A52]" style="min-height: 400px;">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col gap-6 max-w-2xl">

            <div class="flex items-center gap-2">
                <span
                    class="bg-[#FACC15] border-2 border-black text-black font-black text-xs px-3 py-1 uppercase tracking-widest">
                    🎮 Tu wishlist inteligente
                </span>
            </div>

            <h1 class="font-black text-4xl sm:text-5xl lg:text-6xl uppercase tracking-tight leading-none text-white">
                Domina<br><span class="text-[#FACC15]">Steam</span>
            </h1>

            <p class="text-blue-200 text-sm sm:text-base max-w-md leading-relaxed">
                Rastrea precios, descubre ofertas y gestiona tu wishlist en un solo lugar.
                Nunca te pierdas las mejores deals de Steam.
            </p>

            <div class="flex items-center gap-3 flex-wrap mt-2">
                <x-button variant="ghost" size="lg" icon="search" href="{{ route('search') }}" id="hero-explore-btn">
                    Explorar juegos
                </x-button>
            </div>

        </div>
    </section>


    {{-- ══════════════════════════════════════ --}}
    {{--  FEATURES                             --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <x-section-title title="¿Qué hace SteamWish?" icon="zap" color="accent" tag="Features" />

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">

            {{-- Feature 1 --}}
            <div class="bg-white border-4 border-black nb-shadow p-6 flex flex-col gap-3">
                <div class="w-12 h-12 bg-[#5DA9D6] border-2 border-black flex items-center justify-center">
                    <i data-lucide="trending-down" class="w-6 h-6 text-white"></i>
                </div>
                <h2 class="font-black text-[#0F3A52] text-lg uppercase tracking-tight">Rastreo de precios</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Monitorea el historial de precios de cualquier juego y recibe alertas cuando baje al precio que tú
                    fijas.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-[#FACC15] border-4 border-black nb-shadow p-6 flex flex-col gap-3">
                <div class="w-12 h-12 bg-[#0F3A52] border-2 border-black flex items-center justify-center">
                    <i data-lucide="heart" class="w-6 h-6 text-white"></i>
                </div>
                <h2 class="font-black text-[#0F3A52] text-lg uppercase tracking-tight">Wishlist avanzada</h2>
                <p class="text-[#0F3A52]/70 text-sm leading-relaxed">
                    Organiza tus juegos deseados, priorízalos y comparte tu wishlist con amigos.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-[#0F3A52] border-4 border-black nb-shadow p-6 flex flex-col gap-3">
                <div class="w-12 h-12 bg-[#FACC15] border-2 border-black flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-6 h-6 text-black"></i>
                </div>
                <h2 class="font-black text-white text-lg uppercase tracking-tight">Predicciones</h2>
                <p class="text-blue-200 text-sm leading-relaxed">
                    Análisis de tendencias para predecir cuándo un juego entrará en oferta basado en su historial.
                </p>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════════ --}}
    {{--  TRENDING GAMES                       --}}
    {{--  Data comes from HomeController       --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="trending" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 border-t-4 border-black">
        <x-section-title title="Trending Esta Semana" icon="trending-up" color="primary" tag="Live" />

        @forelse($trendingGames as $game)
            <x-game-card :game="$game" />
        @empty
            <div class="bg-white border-4 border-black nb-shadow p-12 text-center">
                <i data-lucide="wifi-off" class="w-10 h-10 mx-auto mb-4 text-gray-300"></i>
                <p class="font-black text-[#0F3A52] text-lg uppercase">Steam API no conectada aún</p>
                <p class="text-gray-400 text-sm mt-2">Los juegos aparecerán aquí cuando se integre la API de Steam.</p>
                <div class="mt-6">
                    <x-button variant="primary" size="md" icon="search" href="{{ route('search') }}"
                        id="trending-search-btn">
                        Explorar manualmente
                    </x-button>
                </div>
            </div>
        @endforelse
    </section>


    {{-- ══════════════════════════════════════ --}}
    {{--  NEW RELEASES                         --}}
    {{--  Data comes from HomeController       --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="new-releases" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-section-title title="New Releases" icon="sparkles" color="secondary" tag="Esta semana" />

        @forelse($newReleases as $game)
            <x-game-card :game="$game" />
        @empty
            <div class="bg-white border-4 border-dashed border-gray-300 p-10 text-center">
                <p class="text-gray-400 text-sm font-mono">// Sin lanzamientos aún — API pendiente</p>
            </div>
        @endforelse
    </section>


    {{-- ══════════════════════════════════════ --}}
    {{--  CTA BANNER                           --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="cta-banner" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div
            class="bg-[#0F3A52] border-4 border-black nb-shadow-lg p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-[#FACC15] border-2 border-black flex items-center justify-center shrink-0 nb-shadow-sm">
                    <i data-lucide="heart" class="w-7 h-7 text-black"></i>
                </div>
                <div>
                    <h2 class="font-black text-white text-xl sm:text-2xl uppercase tracking-tight leading-tight">
                        Crea tu Wishlist Definitiva
                    </h2>
                    <p class="text-blue-200 text-sm font-medium mt-0.5">
                        Recibe alertas de precios y nunca te pierdas una oferta
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection
