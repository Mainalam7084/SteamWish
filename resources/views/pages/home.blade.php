@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

@section('content')

    {{-- ══════════════════════════════════════ --}}
    {{--  HERO                                  --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="hero" class="relative border-b-4 border-black overflow-hidden bg-[#0F3A52]" style="min-height:220px;">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col gap-4">
            <span class="bg-[#FACC15] border-2 border-black text-black font-black text-xs px-3 py-1 uppercase tracking-widest self-start">
                🎮 Tu wishlist inteligente
            </span>
            <h1 class="font-black text-4xl sm:text-6xl uppercase tracking-tight leading-none text-white">
                Domina<br><span class="text-[#FACC15]">Steam</span>
            </h1>
            <p class="text-blue-200 text-sm max-w-md leading-relaxed">
                Rastrea precios, descubre ofertas y gestiona tu wishlist en un solo lugar.
            </p>
            <div>
                <a href="{{ route('search') }}"
                   id="hero-explore-btn"
                   class="inline-flex items-center gap-2 bg-[#FACC15] border-4 border-black text-black font-black uppercase text-sm px-6 py-3
                          shadow-[4px_4px_0_0_#000] hover:shadow-[2px_2px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                    <i data-lucide="search" class="w-4 h-4"></i> Explorar juegos
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════ --}}
    {{--  DASHBOARD                             --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- TOP: Two columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- LEFT: Most Played --}}
            <section id="most-played-section">
                <div class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#0F3A52]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Most Played</h2>
                    <span class="bg-[#FACC15] border-2 border-black text-black text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Live</span>
                </div>
                {{-- Skeleton --}}
                <div id="most-played-skeleton" class="flex flex-col gap-3">
                    @for($i = 0; $i < 6; $i++)
                        <div class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#0F3A52]">
                            <div class="w-8 h-8 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="w-28 h-16 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="flex-1 h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="w-14 h-6 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                        </div>
                    @endfor
                </div>
                {{-- Real data --}}
                <div id="most-played-list" class="flex flex-col gap-3 hidden"></div>
                {{-- Empty state --}}
                <div id="most-played-empty" class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

            {{-- RIGHT: Trending --}}
            <section id="trending-section">
                <div class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#16A34A]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Trending</h2>
                    <span class="bg-[#16A34A] border-2 border-black text-white text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Hot 🔥</span>
                </div>
                {{-- Skeleton --}}
                <div id="trending-skeleton" class="flex flex-col gap-3">
                    @for($i = 0; $i < 6; $i++)
                        <div class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#16A34A]">
                            <div class="w-28 h-16 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="flex-1 h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="w-14 h-6 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                        </div>
                    @endfor
                </div>
                {{-- Real data --}}
                <div id="trending-list" class="flex flex-col gap-3 hidden"></div>
                {{-- Empty state --}}
                <div id="trending-empty" class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

        </div>

        {{-- BOTTOM: Upcoming carousel --}}
        <section id="upcoming-section" class="pt-8 border-t-4 border-black border-dashed">
            <div class="flex items-center justify-between mb-6 bg-[#0F3A52] px-4 py-3 border-4 border-black shadow-[4px_4px_0_0_#000]">
                <h2 class="font-black text-2xl uppercase text-white">Upcoming Games</h2>
                <span class="text-blue-200 uppercase font-bold text-xs tracking-widest hidden sm:block">Próximos lanzamientos</span>
            </div>

            {{-- Skeleton --}}
            <div id="upcoming-skeleton" class="flex gap-6 overflow-x-auto pb-4">
                @for($i = 0; $i < 6; $i++)
                    <div class="shrink-0 w-72 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52]">
                        <div class="w-full aspect-[460/215] bg-gray-200 animate-pulse border-b-4 border-black"></div>
                        <div class="p-3 flex flex-col gap-2">
                            <div class="h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="h-4 w-2/3 bg-gray-200 animate-pulse rounded"></div>
                            <div class="h-5 w-16 bg-gray-200 animate-pulse border-2 border-black mt-1"></div>
                        </div>
                    </div>
                @endfor
            </div>
            {{-- Real data --}}
            <div id="upcoming-list" class="flex gap-6 overflow-x-auto pb-4 hide-scrollbar hidden"></div>
            {{-- Empty state --}}
            <div id="upcoming-empty" class="hidden p-6 bg-white border-4 border-black font-bold text-gray-400 text-center">
                No se pudo cargar. Intenta más tarde.
            </div>
        </section>

    </div>

@endsection

@push('scripts')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────
    function show(id)   { document.getElementById(id)?.classList.remove('hidden'); }
    function hide(id)   { document.getElementById(id)?.classList.add('hidden'); }

    function priceTag(game, accentClass = 'bg-[#FACC15] text-black') {
        const label = game.discount > 0
            ? `-${game.discount}% · ${game.price}`
            : game.price;
        return `<span class="shrink-0 ${accentClass} border-2 border-black px-2 py-0.5 font-black text-xs">${label}</span>`;
    }

    function gameUrl(appid) {
        return `/game?appid=${appid}`;
    }

    // ─────────────────────────────────────────────
    // Render: Most Played list item
    // ─────────────────────────────────────────────
    function renderMostPlayed(games) {
        const container = document.getElementById('most-played-list');
        if (!games.length) { show('most-played-empty'); return; }

        container.innerHTML = games.map((g, i) => {
            const rank    = i + 1;
            const badgeCls = rank <= 3 ? 'bg-[#FACC15] text-black' : 'bg-[#0F3A52] text-white';
            const thumb   = g.image
                ? `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">`
                : `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

            return `
            <a href="${gameUrl(g.appid)}"
               class="flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                <span class="shrink-0 w-8 h-8 flex items-center justify-center border-2 border-black font-black text-sm ${badgeCls}">#${rank}</span>
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${priceTag(g)}
            </a>`;
        }).join('');

        hide('most-played-skeleton');
        show('most-played-list');
        lucide.createIcons();
    }

    // ─────────────────────────────────────────────
    // Render: Trending list item
    // ─────────────────────────────────────────────
    function renderTrending(games) {
        const container = document.getElementById('trending-list');
        if (!games.length) { show('trending-empty'); return; }

        container.innerHTML = games.map(g => {
            const thumb = g.image
                ? `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">`
                : `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

            const badge = g.discount > 0
                ? `<span class="shrink-0 bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5">-${g.discount}%</span>`
                : `<span class="shrink-0 bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-0.5">${g.price}</span>`;

            return `
            <a href="${gameUrl(g.appid)}"
               class="flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#16A34A] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${badge}
            </a>`;
        }).join('');

        hide('trending-skeleton');
        show('trending-list');
    }

    // ─────────────────────────────────────────────
    // Render: Upcoming cards carousel
    // ─────────────────────────────────────────────
    function renderUpcoming(games) {
        const container = document.getElementById('upcoming-list');
        if (!games.length) { show('upcoming-empty'); return; }

        container.innerHTML = games.map(g => {
            const img = g.image
                ? `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">`
                : `<div class="w-full aspect-[460/215] bg-[#0F3A52] border-b-4 border-black flex items-center justify-center">
                       <i data-lucide="calendar" class="w-10 h-10 text-[#5DA9D6]"></i>
                   </div>`;

            return `
            <a href="${gameUrl(g.appid)}"
               class="group block w-72 shrink-0 bg-white border-4 border-black
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6]
                      hover:-translate-y-2 hover:-translate-x-1 transition-all">
                ${img}
                <div class="p-3 flex flex-col gap-1">
                    <h3 class="font-black text-sm uppercase text-[#0F3A52] line-clamp-2 group-hover:text-[#5DA9D6] transition-colors leading-tight">${g.name}</h3>
                    <div class="flex items-center justify-between mt-1">
                        ${priceTag(g)}
                        <span class="text-gray-400 text-xs font-bold uppercase">Coming Soon</span>
                    </div>
                </div>
            </a>`;
        }).join('');

        hide('upcoming-skeleton');
        show('upcoming-list');
        lucide.createIcons();
    }

    // ─────────────────────────────────────────────
    // Fetch and render all sections
    // ─────────────────────────────────────────────
    async function loadHomeData() {
        try {
            const res  = await fetch('{{ route("api.home-data") }}');
            const data = await res.json();

            renderMostPlayed(data.mostPlayed ?? []);
            renderTrending(data.trending    ?? []);
            renderUpcoming(data.upcoming    ?? []);
        } catch (err) {
            console.error('Home data load failed:', err);
            // Show empty states on error
            ['most-played', 'trending', 'upcoming'].forEach(key => {
                hide(`${key}-skeleton`);
                show(`${key}-empty`);
            });
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', loadHomeData);
</script>
@endpush
