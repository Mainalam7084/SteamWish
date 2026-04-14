@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

@section('content')

    {{-- ══════════════════════════════════════ --}}
    {{--  HERO SECTION                         --}}
    {{-- ══════════════════════════════════════ --}}
    @if (isset($featuredGame) && $featuredGame)
        <section id="hero" class="relative border-b-4 border-black overflow-hidden bg-[#0F3A52]"
            style="min-height: 400px;">
            <!-- BG image -->
            <div class="absolute inset-0">
                <img src="{{ $featuredGame['image'] ?? 'https://placehold.co/1200x500/1E1E1E/0F3A52' }}"
                    alt="{{ $featuredGame['name'] ?? 'Destacado' }}" class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0F3A52] via-[#0F3A52]/80 to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col gap-6 max-w-2xl">
                <div class="flex items-center gap-2">
                    <span
                        class="bg-[#FACC15] border-2 border-black text-black font-black text-xs px-3 py-1 uppercase tracking-widest nb-shadow-sm">
                        🔥 Oferta Destacada
                    </span>
                </div>

                <h1
                    class="font-black text-4xl sm:text-5xl lg:text-7xl uppercase tracking-tight leading-none text-white drop-shadow-md">
                    {{ $featuredGame['name'] }}
                </h1>

                <div class="flex items-center gap-4 flex-wrap mt-2">
                    <div class="flex items-baseline gap-3 bg-black/70 p-2 border-4 border-black nb-shadow">
                        <span
                            class="text-[#FACC15] font-black text-3xl">{{ isset($featuredGame['price']) && $featuredGame['price'] !== 'Free' ? $featuredGame['price'] : 'Free to Play' }}</span>
                        @if (isset($featuredGame['discount']) && $featuredGame['discount'] > 0)
                            <span
                                class="bg-[#16A34A] border-2 border-black text-white font-black text-lg px-2 py-0.5 nb-shadow-sm">-{{ $featuredGame['discount'] }}%</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap mt-4">
                    <x-button variant="primary" size="lg" icon="shopping-cart">
                        Ver en Steam
                    </x-button>
                    <x-button variant="secondary" size="lg" icon="heart">
                        Añadir a Wishlist
                    </x-button>
                </div>
            </div>
        </section>
    @else
        <section id="hero" class="relative border-b-4 border-black overflow-hidden bg-[#0F3A52]"
            style="min-height: 200px;">
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <h1 class="font-black text-4xl uppercase tracking-tight leading-none text-white">Dashboard <span
                        class="text-[#FACC15]">Steam</span></h1>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════ --}}
    {{--  DASHBOARD LAYOUT                     --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-[#f5f5f5]">

        {{-- TOP SECTION: Two Columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

            {{-- LEFT COLUMN: Most Played Games --}}
            <section id="most-played">
                <div
                    class="flex items-center justify-between mb-6 border-b-4 border-black pb-2 bg-white px-4 py-2 nb-shadow">
                    <h2 class="font-black text-xl sm:text-2xl uppercase text-[#0F3A52] flex items-center gap-2">
                        Most Played Games
                    </h2>
                    <span
                        class="bg-[#FACC15] border-2 border-black text-black text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0px_0px_#000] hidden sm:block">Live</span>
                </div>

                <div class="flex flex-col gap-3">
                    @forelse($mostPlayedGames as $index => $game)
                        <x-game-list-item :game="$game" :rank="$index + 1" />
                    @empty
                        <div class="p-6 bg-white border-4 border-black text-center font-bold">Sin datos de Most Played.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- RIGHT COLUMN: Trending Games --}}
            <section id="trending">
                <div
                    class="flex items-center justify-between mb-6 border-b-4 border-black pb-2 bg-white px-4 py-2 nb-shadow">
                    <h2 class="font-black text-xl sm:text-2xl uppercase text-[#0F3A52] flex items-center gap-2">
                        Trending Games
                    </h2>
                    <span
                        class="bg-[#16A34A] border-2 border-black text-white text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0px_0px_#000] hidden sm:block">24h
                        Hot</span>
                </div>

                <div class="flex flex-col gap-3">
                    @forelse($trendingGames as $game)
                        <x-trending-item :game="$game" />
                    @empty
                        <div class="p-6 bg-white border-4 border-black text-center font-bold">Sin datos de Trending.</div>
                    @endforelse
                </div>
            </section>

        </div>

        {{-- BOTTOM SECTION: Upcoming Games --}}
        <section id="upcoming" class="pt-8 border-t-4 border-black border-dashed">
            <div class="flex items-center justify-between mb-6 bg-[#0F3A52] px-4 py-3 border-4 border-black nb-shadow">
                <h2 class="font-black text-2xl uppercase text-white flex items-center gap-2">
                    Upcoming Games
                </h2>
                <span class="text-blue-200 uppercase font-bold text-xs tracking-widest hidden sm:inline-block">Próximos
                    lanzamientos</span>
            </div>

            <div class="carousel-container overflow-x-auto pb-8 hide-scrollbar">
                <div class="flex gap-6 w-max px-1">
                    @forelse($upcomingGames as $game)
                        <x-upcoming-card :game="$game" />
                    @empty
                        <div class="p-6 bg-white border-4 border-black font-bold">Sin datos de Upcoming Releases.</div>
                    @endforelse
                </div>
            </div>
        </section>

    </div>

@endsection

@push('scripts')
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .hide-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
@endpush
