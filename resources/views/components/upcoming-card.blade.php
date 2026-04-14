@props(['game'])

@php
    $appid       = $game['appid']        ?? '';
    $name        = $game['name']         ?? 'Unknown Game';
    $image       = $game['image']        ?? null;
    $releaseDate = $game['release_date'] ?? null;

    if ($game['is_free'] ?? false) {
        $price = 'Free';
    } elseif (isset($game['price']) && $game['price'] !== null) {
        $price = number_format((float) $game['price'], 2) . '€';
    } else {
        $price = 'TBA';
    }
@endphp

<a href="/game?appid={{ $appid }}"
   class="group block w-52 shrink-0 bg-white border-4 border-black
          shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6]
          hover:-translate-y-2 hover:-translate-x-1 transition-all">

    {{-- Image --}}
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}"
             class="w-full aspect-[460/215] object-cover border-b-4 border-black">
    @else
        <div class="w-full aspect-[460/215] bg-[#0F3A52] border-b-4 border-black
                    flex items-center justify-center">
            <i data-lucide="calendar" class="w-10 h-10 text-[#5DA9D6]"></i>
        </div>
    @endif

    {{-- Info --}}
    <div class="p-3 flex flex-col gap-1">
        <h3 class="font-black text-sm uppercase text-[#0F3A52] line-clamp-2
                   group-hover:text-[#5DA9D6] transition-colors leading-tight">
            {{ $name }}
        </h3>

        <div class="flex items-center justify-between mt-1">
            <span class="bg-[#FACC15] border-2 border-black px-2 py-0.5
                         font-black text-xs text-black">
                {{ $price }}
            </span>
            @if($releaseDate)
                <span class="text-gray-400 text-xs font-bold">{{ $releaseDate }}</span>
            @else
                <span class="text-gray-400 text-xs font-bold uppercase">Coming Soon</span>
            @endif
        </div>
    </div>
</a>
