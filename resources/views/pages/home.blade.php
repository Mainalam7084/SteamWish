@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

<x-home-loader />

@section('content')

    {{-- ══════════════════════════════════════ --}}
    {{--  HERO — Ofertas Trending               --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="hero" class="relative border-b-4 border-black bg-[#0F3A52] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-black text-2xl sm:text-3xl uppercase text-white tracking-tight">Ofertas <span
                            class="text-[#FACC15]">Trending</span></h2>
                    <span
                        class="hidden sm:block bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-1 uppercase tracking-widest shadow-[2px_2px_0_0_#000]">¡Mejor
                        precio!</span>
                </div>
            </div>

            {{-- Skeleton: horizontal scroll --}}
            <div id="deals-skeleton" class="flex gap-4 overflow-x-auto pb-3 sw-scrollbar">
                @for ($i = 0; $i < 8; $i++)
                    <div class="shrink-0 w-52 bg-white/10 border-4 border-white/20 animate-pulse">
                        <div class="w-full aspect-[460/215] bg-white/15"></div>
                        <div class="p-3 flex flex-col gap-2">
                            <div class="h-3 bg-white/20 rounded"></div>
                            <div class="h-3 w-2/3 bg-white/15 rounded"></div>
                            <div class="h-5 w-14 bg-[#FACC15]/25 mt-1"></div>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Real cards: all in one scrollable row --}}
            <div id="deals-list" class="hidden flex gap-4 overflow-x-auto pb-3 sw-scrollbar"></div>

            {{-- Empty --}}
            <div id="deals-empty" class="hidden text-blue-200 text-sm font-bold text-center py-10">
                No hay ofertas disponibles ahora.
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
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#0F3A52]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Most Played</h2>
                    <span
                        class="bg-[#FACC15] border-2 border-black text-black text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Live</span>
                </div>
                {{-- Skeleton --}}
                <div id="most-played-skeleton" class="flex flex-col gap-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#0F3A52]">
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
                <div id="most-played-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

            {{-- RIGHT: Trending --}}
            <section id="trending-section">
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#16A34A]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Trending</h2>
                    <span
                        class="bg-[#16A34A] border-2 border-black text-white text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Hot
                    </span>
                </div>
                {{-- Skeleton --}}
                <div id="trending-skeleton" class="flex flex-col gap-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#16A34A]">
                            <div class="w-28 h-16 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="flex-1 h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="w-14 h-6 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                        </div>
                    @endfor
                </div>
                {{-- Real data --}}
                <div id="trending-list" class="flex flex-col gap-3 hidden"></div>
                {{-- Empty state --}}
                <div id="trending-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

        </div>

        {{-- BOTTOM: Upcoming carousel --}}
        <section id="upcoming-section" class="pt-8 border-t-4 border-black border-dashed">
            <div
                class="flex items-center justify-between mb-6 bg-[#0F3A52] px-4 py-3 border-4 border-black shadow-[4px_4px_0_0_#000]">
                <h2 class="font-black text-2xl uppercase text-white">Upcoming Games</h2>
                <span class="text-blue-200 uppercase font-bold text-xs tracking-widest hidden sm:block">Próximos
                    lanzamientos</span>
            </div>

            {{-- Skeleton --}}
            <div id="upcoming-skeleton" class="flex gap-6 overflow-x-auto pb-4">
                @for ($i = 0; $i < 6; $i++)
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
            <div id="upcoming-list" class="flex gap-6 overflow-x-auto pb-4 sw-scrollbar hidden"></div>
            {{-- Empty state --}}
            <div id="upcoming-empty" class="hidden p-6 bg-white border-4 border-black font-bold text-gray-400 text-center">
                No se pudo cargar. Intenta más tarde.
            </div>
        </section>

    </div>

@endsection

@push('scripts')
    <style>
        /* ── Custom Neo-Brutalist scrollbar ────────── */
        .sw-scrollbar::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        .sw-scrollbar::-webkit-scrollbar-track {
            background: #0F3A52;
            border: 2px solid #000;
        }

        .sw-scrollbar::-webkit-scrollbar-thumb {
            background: #FACC15;
            border: 2px solid #000;
        }

        .sw-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #fde047;
        }

        .sw-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #FACC15 #0F3A52;
        }

        /* keep the old class for anything that still uses it */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        // ─────────────────────────────────────────────
        // Helpers
        // ─────────────────────────────────────────────
        function show(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function hide(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        function priceTag(game, accentClass = 'bg-[#FACC15] text-black') {
            const label = game.discount > 0 ?
                `-${game.discount}% · ${game.price}` :
                game.price;
            return `<span class="shrink-0 ${accentClass} border-2 border-black px-2 py-0.5 font-black text-xs">${label}</span>`;
        }

        function gameUrl(appid) {
            return `/game?appid=${appid}`;
        }

        // Shared set of saved appids (populated after loading)
        let savedAppids = new Set();

        function heartBtn(appid, classes = 'absolute top-2 right-2') {
            const saved = savedAppids.has(Number(appid));
            const active = saved ? 'bg-[#FACC15] text-black' : 'bg-white text-[#0F3A52]';
            return `<button
            data-appid="${appid}"
            class="wishlist-btn shrink-0 w-8 h-8 border-2 border-black flex items-center justify-center
                   shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]
                   transition-all ${active} ${classes}"
            aria-label="Toggle wishlist">
            <i data-lucide="heart" class="w-3.5 h-3.5"></i>
        </button>`;
        }

        // ─────────────────────────────────────────────
        // Render: Most Played list item
        // ─────────────────────────────────────────────
        function renderMostPlayed(games) {
            const container = document.getElementById('most-played-list');
            if (!games.length) {
                show('most-played-empty');
                return;
            }

            container.innerHTML = games.map((g, i) => {
                const rank = i + 1;
                const badgeCls = rank <= 3 ? 'bg-[#FACC15] text-black' : 'bg-[#0F3A52] text-white';
                const thumb = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
                    `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                <span class="shrink-0 w-8 h-8 flex items-center justify-center border-2 border-black font-black text-sm ${badgeCls}">#${rank}</span>
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${priceTag(g)}
                ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
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
            if (!games.length) {
                show('trending-empty');
                return;
            }

            container.innerHTML = games.map(g => {
                const thumb = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
                    `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

                const badge = g.discount > 0 ?
                    `<span class="shrink-0 bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5">-${g.discount}% · ${g.price}</span>` :
                    `<span class="shrink-0 bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-0.5">${g.price}</span>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#16A34A] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${badge}
                ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
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
            if (!games.length) {
                show('upcoming-empty');
                return;
            }

            container.innerHTML = games.map(g => {
                const img = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">` :
                    `<div class="w-full aspect-[460/215] bg-[#0F3A52] border-b-4 border-black flex items-center justify-center">
                       <i data-lucide="calendar" class="w-10 h-10 text-[#5DA9D6]"></i>
                   </div>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative group block w-72 shrink-0 bg-white border-4 border-black
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6]
                      hover:-translate-y-2 hover:-translate-x-1 transition-all">
                ${img}
                ${heartBtn(g.appid, 'absolute top-2 right-2')}
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
        // Render: Hero Deals Carousel (discounted trending games)
        // ─────────────────────────────────────────────
        function renderDeals(allGames) {
            const deals = allGames
                .filter(g => g.discount > 0)
                .sort((a, b) => b.discount - a.discount);

            const skeleton = document.getElementById('deals-skeleton');
            const list = document.getElementById('deals-list');
            const empty = document.getElementById('deals-empty');

            if (!deals.length) {
                skeleton?.classList.add('hidden');
                empty?.classList.remove('hidden');
                return;
            }

            list.innerHTML = deals.map(g => {
                const img = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">` :
                    `<div class="w-full aspect-[460/215] bg-[#0F3A52]/60 border-b-4 border-black flex items-center justify-center"><i data-lucide="gamepad-2" class="w-8 h-8 text-blue-300"></i></div>`;

                const discountBadge = g.discount > 0 ?
                    `<span class="inline-block bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5 shadow-[2px_2px_0_0_#000]">-${g.discount}%</span>` :
                    '';

                return `
                <a href="${gameUrl(g.appid)}"
                   class="relative group block shrink-0 w-52 bg-[#F5F5F5] border-4 border-black
                          shadow-[4px_4px_0_0_#FACC15] hover:shadow-[6px_6px_0_0_#FACC15]
                          hover:-translate-y-1 hover:-translate-x-1 transition-all duration-200 flex flex-col">
                    ${img}
                    ${heartBtn(g.appid, 'absolute top-2 right-2')}
                    <div class="p-3 flex flex-col flex-grow justify-between bg-white">
                        <h3 class="text-xs font-black uppercase text-[#0F3A52] line-clamp-2 group-hover:text-[#5DA9D6] transition-colors leading-tight mb-2">${g.name}</h3>
                        <div class="flex items-center justify-between gap-1 mt-auto flex-wrap">
                            <span class="inline-block bg-[#FACC15] border-2 border-black px-2 py-0.5 font-black text-xs text-black shadow-[2px_2px_0_0_#000]">${g.price}</span>
                            ${discountBadge}
                        </div>
                    </div>
                </a>`;
            }).join('');

            skeleton?.classList.add('hidden');
            list.classList.remove('hidden');
            lucide.createIcons();
        }

        // ─────────────────────────────────────────────
        // Cache helpers (localStorage, 2-hour TTL)
        // ─────────────────────────────────────────────
        const CACHE_KEY = 'sw_home_data';
        const CACHE_TTL = 2 * 60 * 60 * 1000; // 2 hours in ms

        function getCached() {
            try {
                const raw = localStorage.getItem(CACHE_KEY);
                if (!raw) return null;
                const { ts, data } = JSON.parse(raw);
                if (Date.now() - ts > CACHE_TTL) { localStorage.removeItem(CACHE_KEY); return null; }
                return data;
            } catch { return null; }
        }

        function setCache(data) {
            try { localStorage.setItem(CACHE_KEY, JSON.stringify({ ts: Date.now(), data })); } catch {}
        }

        // ─────────────────────────────────────────────
        // Render everything from a data object
        // ─────────────────────────────────────────────
        function renderAll(data, ids) {
            savedAppids = new Set(ids.map(Number));
            renderMostPlayed(data.mostPlayed ?? []);
            renderTrending(data.trending ?? []);
            renderUpcoming(data.upcoming ?? []);
            renderDeals(data.trending ?? []);
        }

        // ─────────────────────────────────────────────
        // Fetch and render all sections
        // ─────────────────────────────────────────────
        async function loadHomeData() {
            const cached = getCached();

            // --- FAST PATH: render from cache immediately ---
            if (cached) {
                // Skip loading screen — data is ready now
                const overlay = document.getElementById('sw-loader-overlay');
                if (overlay) overlay.remove();

                // Wishlist IDs are user-specific, always fetch live
                const ids = await fetch('{{ route('api.wishlist-ids') }}')
                    .then(r => r.ok ? r.json() : [])
                    .catch(() => []);

                renderAll(cached, ids);
                return;
            }

            // --- SLOW PATH: fresh fetch ---
            try {
                const homePromise = fetch('{{ route('api.home-data') }}').then(r => r.json());
                const idsPromise  = fetch('{{ route('api.wishlist-ids') }}')
                    .then(r => r.ok ? r.json() : [])
                    .catch(() => []);

                const [data, ids] = await Promise.all([homePromise, idsPromise]);

                setCache(data); // save for next visit
                renderAll(data, ids);
            } catch (err) {
                console.error('Home data load failed:', err);
                ['most-played', 'trending', 'upcoming'].forEach(key => {
                    hide(`${key}-skeleton`);
                    show(`${key}-empty`);
                });
                // also hide deals skeleton
                document.getElementById('deals-skeleton')?.classList.add('hidden');
                document.getElementById('deals-empty')?.classList.remove('hidden');
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', loadHomeData);
    </script>
@endpush
