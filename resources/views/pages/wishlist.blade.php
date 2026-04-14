@extends('layouts.app')

@section('title', 'My Wishlist - SteamWish')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8 border-b-4 border-black pb-4">
            <h1 class="font-black text-3xl md:text-4xl uppercase text-[#0F3A52]">My Wishlist</h1>
            @if(count($games) > 0)
                <span class="bg-[#FACC15] border-2 border-black text-black font-black px-4 py-1 text-sm uppercase shadow-[2px_2px_0_0_#000]">
                    {{ count($games) }} game{{ count($games) !== 1 ? 's' : '' }} saved
                </span>
            @endif
        </div>

        @if(count($games) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($games as $game)
                    <div class="group bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6] hover:-translate-y-1 hover:-translate-x-1 transition-all flex flex-col overflow-hidden">

                        {{-- Image --}}
                        <a href="/game?appid={{ $game['appid'] }}" class="relative overflow-hidden border-b-4 border-black block">
                            <img src="{{ $game['image'] }}"
                                 alt="{{ $game['name'] }}"
                                 class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/300x180/0F3A52/5DA9D6?text=No+Image'">
                        </a>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col gap-3 flex-1">
                            <a href="/game?appid={{ $game['appid'] }}"
                               class="font-black text-sm uppercase text-[#0F3A52] line-clamp-2 hover:text-[#5DA9D6] transition-colors">
                                {{ $game['name'] }}
                            </a>

                            <div class="mt-auto flex items-center justify-between border-t-2 border-gray-100 pt-3">
                                <span class="font-black text-[#5DA9D6] text-lg">
                                    @if($game['discount'] > 0)
                                        <span class="bg-[#16A34A] text-white text-xs font-black px-1 border border-black mr-1">-{{ $game['discount'] }}%</span>
                                    @endif
                                    {{ $game['price'] }}
                                </span>

                                {{-- Remove button --}}
                                <button
                                    data-appid="{{ $game['appid'] }}"
                                    class="wishlist-btn border-2 border-black font-black text-[10px] uppercase px-3 py-1.5
                                           bg-[#FACC15] text-black
                                           shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]
                                           transition-all"
                                    aria-label="Remove from wishlist">
                                    Saved
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty state --}}
            <div class="mt-12 text-center bg-white border-4 border-black p-12 shadow-[8px_8px_0_0_#0F3A52]">
                <i data-lucide="ghost" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                <h2 class="text-2xl font-black uppercase text-[#0F3A52] mb-4">Your wishlist is empty</h2>
                <p class="text-gray-500 mb-8">Explore trending and upcoming games and click the heart to save them here.</p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-[#FACC15] border-4 border-black font-black uppercase
                          px-6 py-3 shadow-[4px_4px_0_0_#000] hover:shadow-[2px_2px_0_0_#000]
                          hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-black text-sm">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Explore Games
                </a>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
<script>
    // When a wishlist button on this page is clicked and item is removed,
    // remove the card from the DOM immediately (optimistic update)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.wishlist-btn');
        if (!btn) return;

        const appid = btn.dataset.appid;
        if (!appid) return;

        // After the toggle response, if removed → fade out the card
        const originalFetch = window._wishlistToggleDone;
        const card = btn.closest('.group');

        btn.addEventListener('wishlist:removed', function () {
            if (card) {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.remove(), 300);
            }
        }, { once: true });
    });
</script>
@endpush
