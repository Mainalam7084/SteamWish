@props([
    'game' => [],
])

@php
    $name = $game['name'] ?? 'Unknown Game';
    $image = $game['image'] ?? 'https://placehold.co/300x180/0F3A52/5DA9D6?text=GAME';
    $price = $game['price'] ?? '29.99';
    $oldPrice = $game['old_price'] ?? null;
    $discount = $game['discount'] ?? null;
    $genre = $game['genre'] ?? '';
    $id = 'game-card-' . Str::slug($name);
@endphp

<article id="{{ $id }}"
    class="group bg-white border-4 border-black nb-shadow nb-hover flex flex-col overflow-hidden cursor-pointer">
    <!-- Image wrapper -->
    <div class="relative overflow-hidden border-b-4 border-black">
        <img src="{{ $image }}" alt="{{ $name }}"
            class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">

        <!-- Discount Badge — accent yellow -->
        @if ($discount)
            <div
                class="absolute top-2 left-2 bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-0.5 nb-shadow-sm badge-pulse uppercase tracking-wider">
                {{ $discount }}
            </div>
        @endif

        <!-- Genre Badge — primary blue -->
        @if ($genre)
            <div
                class="absolute top-2 right-2 bg-[#0F3A52] border-2 border-[#5DA9D6] text-[#5DA9D6] font-bold text-[10px] px-2 py-0.5 uppercase tracking-wider">
                {{ $genre }}
            </div>
        @endif

        <!-- Wishlist Heart Button -->
        <button id="{{ $id }}-heart" data-heart aria-label="Agregar a wishlist"
            class="absolute bottom-2 right-2 w-9 h-9 bg-white border-2 border-black flex items-center justify-center text-[#0F3A52] nb-shadow-sm hover:bg-[#5DA9D6] hover:text-white hover:border-black transition-all duration-150">
            <i data-lucide="heart" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- Card Body -->
    <div class="p-4 flex flex-col gap-2 flex-1">
        <!-- Game Name -->
        <h3
            class="font-black text-[#0F3A52] text-sm sm:text-base leading-tight line-clamp-2 uppercase tracking-tight group-hover:text-[#5DA9D6] transition-colors duration-150">
            {{ $name }}
        </h3>

        <!-- Pricing Row -->
        <div class="mt-auto pt-2 flex items-center justify-between border-t-2 border-gray-200">
            <div class="flex items-baseline gap-2">
                <span class="font-black text-[#5DA9D6] text-lg leading-none">${{ $price }}</span>
                @if ($oldPrice)
                    <span class="font-mono text-gray-400 text-xs line-through">${{ $oldPrice }}</span>
                @endif
            </div>

            <!-- Add to Wishlist button — accent yellow -->
            <button id="{{ $id }}-cart"
                class="bg-[#FACC15] border-2 border-black text-black font-black text-[10px] uppercase tracking-wider px-3 py-1.5 nb-shadow-sm hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[3px_3px_0px_black] transition-all duration-100">
                + Wishlist
            </button>
        </div>
    </div>
</article>
