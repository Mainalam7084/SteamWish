@extends('layouts.app')

@section('title', 'Search Results - SteamWish')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-8">
            <a href="/"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#FACC15] text-[#0F3A52] font-black uppercase text-sm border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[2px_2px_0_0_#0F3A52] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                <i data-lucide="home" class="w-5 h-5"></i> Back to Home
            </a>
        </div>

        <div
            class="bg-white border-4 border-black shadow-[8px_8px_0_0_#0F3A52] p-6 mb-8 uppercase flex flex-col md:flex-row md:items-center gap-4">
            <h1 class="text-3xl md:text-5xl font-black text-[#0F3A52]">
                Search Results For
            </h1>
            <span class="bg-[#5DA9D6] text-white px-4 py-2 border-4 border-black text-2xl md:text-4xl font-black break-all">
                "{{ $query }}"
            </span>
        </div>

        @if (empty($results))
            <div class="bg-[#F5F5F5] border-4 border-black shadow-[8px_8px_0_0_#0F3A52] p-12 text-center text-[#0F3A52]">
                <i data-lucide="frown" class="w-20 h-20 mx-auto mb-6 text-[#0F3A52]"></i>
                <h2 class="text-3xl md:text-4xl font-black uppercase mb-4">No games found</h2>
                <p class="font-bold text-xl">We couldn't find any matches for that search. Try another query!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($results as $game)
                    @php
                        $data = $game['data'] ?? [];
                        $appid = $data['steam_appid'] ?? '';
                        $name = $data['name'] ?? 'Unknown Game';
                        $headerImage =
                            $data['header_image'] ?? 'https://placehold.co/460x215/F5F5F5/0F3A52?text=No+Image';
                        $price = $data['price_overview']['final_formatted'] ?? 'Free';
                        $inWishlist = $game['in_wishlist'] ?? false;
                    @endphp
                    <a href="/game?appid={{ $appid }}"
                        class="relative group block bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6] hover:-translate-y-2 hover:-translate-x-2 transition-all duration-200 flex flex-col h-full bg-[#F5F5F5]">
                        <img src="{{ $headerImage }}" alt="{{ $name }}"
                            class="w-full aspect-[460/215] object-cover border-b-4 border-black bg-gray-200">
                        
                        <button
                            data-appid="{{ $appid }}"
                            class="wishlist-btn absolute top-2 right-2 shrink-0 w-8 h-8 border-2 border-black flex items-center justify-center shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all {{ $inWishlist ? 'bg-[#FACC15] text-black' : 'bg-white text-[#0F3A52] hover:bg-[#5DA9D6] hover:text-white' }}"
                            aria-label="Toggle wishlist">
                            <i data-lucide="heart" class="w-3.5 h-3.5"></i>
                        </button>
                        <div class="p-5 flex flex-col flex-grow justify-between bg-white border-t-2 border-transparent">
                            <h3
                                class="text-xl font-black uppercase text-[#0F3A52] mb-4 line-clamp-2 group-hover:text-[#5DA9D6] transition-colors">
                                {{ $name }}</h3>
                            <div class="mt-auto flex items-center justify-between">
                                <span
                                    class="inline-block bg-[#FACC15] border-4 border-black px-4 py-1.5 font-black text-sm md:text-base text-[#0F3A52]">{{ $price }}</span>
                                <div class="text-[#0F3A52] opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="arrow-up-right" class="w-6 h-6"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
