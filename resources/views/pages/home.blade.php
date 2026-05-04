@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

<x-home-loader />

@section('content')

    <!-- HERO — Ofertas Trending -->
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
                <div class="flex gap-3">
                    <button id="deal-prev"
                        class="w-10 h-10 bg-white border-2 border-black shadow-[2px_2px_0_0_#000] hover:shadow-[4px_4px_0_0_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all flex items-center justify-center">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>
                    <button id="deal-next"
                        class="w-10 h-10 bg-[#FACC15] border-2 border-black shadow-[2px_2px_0_0_#000] hover:shadow-[4px_4px_0_0_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all flex items-center justify-center">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            {{-- Skeleton: horizontal scroll --}}
            <div id="deals-skeleton" class="flex gap-8 overflow-x-auto pb-8 pt-4 px-2 sw-scrollbar">
                @for ($i = 0; $i < 8; $i++)
                    <div
                        class="shrink-0 w-[20em] h-[26em] bg-white/10 border-4 border-white/20 animate-pulse rounded-[0.6em]">
                    </div>
                @endfor
            </div>

            {{-- Real cards: todos en scrollable row --}}
            <div id="deals-list" class="hidden flex gap-8 overflow-x-auto pb-10 pt-4 px-2 sw-scrollbar scroll-smooth"></div>

            {{-- Empty --}}
            <div id="deals-empty" class="hidden text-blue-200 text-sm font-bold text-center py-10">
                No hay ofertas disponibles ahora.
            </div>

        </div>
    </section>

    <!-- Dashboard -->
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

        /* ── Custom Neo-Brutalist Card Component ────────── */
        .card {
            --primary: #FACC15;
            --primary-hover: #fde047;
            --secondary: #0F3A52;
            --secondary-hover: #174b6b;
            --accent: #16A34A;
            --text: #050505;
            --bg: #ffffff;
            --shadow-color: #000000;
            --pattern-color: #cfcfcf;

            position: relative;
            width: 20em;
            background: var(--bg);
            border: 0.35em solid var(--text);
            border-radius: 0.6em;
            box-shadow:
                0.7em 0.7em 0 var(--shadow-color),
                inset 0 0 0 0.15em rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            overflow: hidden;
            font-family: ui-sans-serif, system-ui, sans-serif;
            transform-origin: center;
            display: block;
        }

        .card:hover {
            transform: translate(-0.4em, -0.4em) scale(1.02);
            box-shadow: 1em 1em 0 var(--shadow-color);
        }

        .card:hover .card-pattern-grid,
        .card:hover .card-overlay-dots {
            opacity: 1;
        }

        .card:active {
            transform: translate(0.1em, 0.1em) scale(0.98);
            box-shadow: 0.5em 0.5em 0 var(--shadow-color);
        }

        .card::before {
            content: "";
            position: absolute;
            top: -1em;
            right: -1em;
            width: 4em;
            height: 4em;
            background: var(--accent);
            transform: rotate(45deg);
            z-index: 1;
        }

        .card::after {
            content: "★";
            position: absolute;
            top: 0.4em;
            right: 0.4em;
            color: var(--text);
            font-size: 1.2em;
            font-weight: bold;
            z-index: 2;
        }

        .card-pattern-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(to right,
                    rgba(0, 0, 0, 0.05) 1px,
                    transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
            background-size: 0.5em 0.5em;
            pointer-events: none;
            opacity: 0.5;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .card-overlay-dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(var(--pattern-color) 1px, transparent 1px);
            background-size: 1em 1em;
            background-position: -0.5em -0.5em;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .bold-pattern {
            position: absolute;
            top: 0;
            right: 0;
            width: 6em;
            height: 6em;
            opacity: 0.15;
            pointer-events: none;
            z-index: 1;
        }

        .card-title-area {
            position: relative;
            padding: 1.4em;
            background: var(--primary);
            color: var(--text);
            font-weight: 800;
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 0.35em solid var(--text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 2;
            overflow: hidden;
        }

        .card-title-area::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(45deg,
                    rgba(0, 0, 0, 0.1),
                    rgba(0, 0, 0, 0.1) 0.5em,
                    transparent 0.5em,
                    transparent 1em);
            pointer-events: none;
            opacity: 0.3;
        }

        .card-tag {
            background: var(--bg);
            color: var(--text);
            font-size: 0.6em;
            font-weight: 800;
            padding: 0.4em 0.8em;
            border: 0.15em solid var(--text);
            border-radius: 0.3em;
            box-shadow: 0.2em 0.2em 0 var(--shadow-color);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transform: rotate(3deg);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .card:hover .card-tag {
            transform: rotate(-2deg) scale(1.1);
            box-shadow: 0.25em 0.25em 0 var(--shadow-color);
        }

        .card-body {
            position: relative;
            padding: 1.5em;
            z-index: 2;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5em;
            padding-top: 1.2em;
            border-top: 0.15em dashed rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .card-actions::before {
            content: "✂";
            position: absolute;
            top: -0.8em;
            left: 50%;
            transform: translateX(-50%) rotate(90deg);
            background: var(--bg);
            padding: 0 0.5em;
            font-size: 1em;
            color: rgba(0, 0, 0, 0.4);
        }

        .price {
            position: relative;
            font-size: 1.8em;
            font-weight: 800;
            color: var(--text);
            background: var(--bg);
            line-height: 1;
        }

        .price::before {
            content: "";
            position: absolute;
            bottom: 0.05em;
            left: 0;
            width: 100%;
            height: 0.2em;
            background: var(--accent);
            z-index: -1;
            opacity: 0.5;
        }

        .price-currency {
            font-size: 0.6em;
            font-weight: 700;
            vertical-align: top;
            margin-right: 0.1em;
        }

        .price-period {
            display: block;
            font-size: 0.4em;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.6);
            margin-top: 0.4em;
            text-transform: uppercase;
        }

        .card-button {
            position: relative;
            background: var(--secondary);
            color: var(--bg);
            font-size: 0.9em;
            font-weight: 700;
            padding: 0.7em 1.2em;
            border: 0.2em solid var(--text);
            border-radius: 0.4em;
            box-shadow: 0.3em 0.3em 0 var(--shadow-color);
            cursor: pointer;
            transition: all 0.2s ease;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.2) 50%,
                    transparent 100%);
            transition: left 0.6s ease;
        }

        .card-button:hover {
            background: var(--secondary-hover);
            transform: translate(-0.1em, -0.1em);
            box-shadow: 0.4em 0.4em 0 var(--shadow-color);
        }

        .card-button:hover::before {
            left: 100%;
        }

        .card-button:active {
            transform: translate(0.1em, 0.1em);
            box-shadow: 0.15em 0.15em 0 var(--shadow-color);
        }

        .dots-pattern {
            position: absolute;
            bottom: 2em;
            left: -2em;
            width: 8em;
            height: 4em;
            opacity: 0.3;
            transform: rotate(-10deg);
            pointer-events: none;
            z-index: 1;
        }

        .accent-shape {
            position: absolute;
            width: 2.5em;
            height: 2.5em;
            background: var(--primary);
            border: 0.15em solid var(--text);
            border-radius: 0.3em;
            transform: rotate(45deg);
            bottom: -1.2em;
            right: 2em;
            z-index: 0;
            transition: transform 0.3s ease;
        }

        .card:hover .accent-shape {
            transform: rotate(55deg) scale(1.1);
        }

        .stamp {
            position: absolute;
            bottom: 1.5em;
            left: 1.5em;
            width: 4em;
            height: 4em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0.15em solid rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            transform: rotate(-15deg);
            opacity: 0.2;
            z-index: 1;
        }

        .stamp-text {
            font-size: 0.6em;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .corner-slice {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 1.5em;
            height: 1.5em;
            background: var(--bg);
            border-right: 0.25em solid var(--text);
            border-top: 0.25em solid var(--text);
            border-radius: 0 0.5em 0 0;
            z-index: 1;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.getElementById('deals-list');
            const prevBtn = document.getElementById('deal-prev');
            const nextBtn = document.getElementById('deal-next');

            if (list && prevBtn && nextBtn) {
                const scrollAmount = 350; // card width + gap approx

                nextBtn.addEventListener('click', () => {
                    if (list.scrollLeft + list.clientWidth >= list.scrollWidth - 20) {
                        // Loop back to start
                        list.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        list.scrollBy({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    }
                });

                prevBtn.addEventListener('click', () => {
                    if (list.scrollLeft <= 10) {
                        // Loop to end
                        list.scrollTo({
                            left: list.scrollWidth,
                            behavior: 'smooth'
                        });
                    } else {
                        list.scrollBy({
                            left: -scrollAmount,
                            behavior: 'smooth'
                        });
                    }
                });
            }
        });
    </script>

    <script>
        window.HomeConfig = {
            routes: {
                wishlistIds: "{{ route('api.wishlist-ids') }}",
                homeData: "{{ route('api.home-data') }}"
            }
        };
    </script>
    @vite(['resources/js/home.js'])
@endpush
