@extends('layouts.app')

@section('title', 'My Wishlist - SteamWish')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-section-title title="My Wishlist" subtitle="Games you're keeping an eye on" />

        @if (count($wishlists) > 0)
            <div class="mt-8 bg-[#FACC15] border-4 border-black p-4 nb-shadow mb-8 font-bold">
                You have {{ count($wishlists) }} games saved! To see their full details, backgrounds jobs will fetch their
                info from Steam API.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($wishlists as $appid)
                    <x-game-card :game="[
                        'appid' => $appid,
                        'name' => 'Steam Game #' . $appid,
                        'image' =>
                            'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/' .
                            $appid .
                            '/header.jpg',
                    ]" />
                @endforeach
            </div>
        @else
            <div class="mt-12 text-center bg-white border-4 border-black p-12 nb-shadow">
                <i data-lucide="ghost" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
                <h2 class="text-2xl font-black uppercase text-[#0F3A52] mb-4">Your wishlist is empty</h2>
                <p class="text-gray-500 mb-6">Explore the trending and upcoming games and click the heart icon to save them
                    here.</p>
                <a href="{{ route('home') }}"
                    class="inline-block bg-[#FACC15] border-2 border-black font-black uppercase tracking-wider px-6 py-3 nb-shadow hover:bg-yellow-300 transition-colors">Explore
                    Games</a>
            </div>
        @endif
    </div>
@endsection
