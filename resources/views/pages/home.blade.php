@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

<x-home-loader />

@section('content')

    <!-- Hero -->
    <section id="hero" class="relative border-b-4 border-black bg-[#0F3A52] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-black text-2xl sm:text-3xl uppercase text-white tracking-tight">Ofertas <span
                            class="text-[#FACC15]">Destacadas</span></h2>
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

            {{-- Skeleton --}}
            <div id="deals-skeleton" class="flex gap-8 overflow-x-auto pb-8 pt-4 px-2 sw-scrollbar">
                @for ($i = 0; $i < 8; $i++)
                    <div
                        class="shrink-0 w-[20em] h-[26em] bg-white/10 border-4 border-white/20 animate-pulse rounded-[0.6em]">
                    </div>
                @endfor
            </div>

            {{-- Tarjetas --}}
            <div id="deals-list" class="hidden flex gap-8 overflow-x-auto pb-10 pt-4 px-2 sw-scrollbar scroll-smooth"></div>

            {{-- Vacío --}}
            <div id="deals-empty" class="hidden text-blue-200 text-sm font-bold text-center py-10">
                No hay ofertas disponibles ahora.
            </div>

        </div>
    </section>

    <!-- Panel -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- Más Jugados --}}
            <section id="most-played-section">
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#0F3A52]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Más Jugados</h2>
                    <span
                        class="bg-[#FACC15] border-2 border-black text-black text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">En Vivo</span>
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
                {{-- Vacío --}}
                <div id="most-played-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

            {{-- Tendencias --}}
            <section id="trending-section">
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#16A34A]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Tendencias</h2>
                    <span
                        class="bg-[#16A34A] border-2 border-black text-white text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Popular
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
                {{-- Vacío --}}
                <div id="trending-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

        </div>

        {{-- Carrusel --}}
        <section id="upcoming-section" class="pt-8 border-t-4 border-black border-dashed">
            <div
                class="flex items-center justify-between mb-6 bg-[#0F3A52] px-4 py-3 border-4 border-black shadow-[4px_4px_0_0_#000]">
                <h2 class="font-black text-2xl uppercase text-white">Próximos Juegos</h2>
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
            {{-- Vacío --}}
            <div id="upcoming-empty" class="hidden p-6 bg-white border-4 border-black font-bold text-gray-400 text-center">
                No se pudo cargar. Intenta más tarde.
            </div>
        </section>

    </div>

@endsection

@push('scripts')
    

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
