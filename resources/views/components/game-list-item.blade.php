@props(['game', 'rank' => 1])

@php
    $appid = $game['appid'] ?? '';
    $name  = $game['name']  ?? 'Unknown Game';
    $image = $game['image'] ?? null;

    if ($game['is_free'] ?? false) {
        $price = 'Free';
    } elseif (isset($game['price']) && $game['price'] !== null) {
        $price = number_format((float) $game['price'], 2) . '€';
    } else {
        $price = '—';
    }
@endphp

<a href="/game?appid={{ $appid }}"
   class="flex items-center gap-3 bg-white border-4 border-black p-2 sm:p-3
          shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[6px_6px_0_0_#5DA9D6]
          hover:-translate-x-1 hover:-translate-y-1 transition-all group min-h-[56px]"
   aria-label="{{ $name }}, precio: {{ $price }}">

    {{-- Rank badge --}}
    <span class="shrink-0 w-9 h-9 flex items-center justify-center
                 border-2 border-black font-black text-xs
                 {{ $rank <= 3 ? 'bg-[#FACC15] text-black' : 'bg-[#0F3A52] text-white' }}"
          aria-hidden="true">
        #{{ $rank }}
    </span>

    {{-- Thumbnail --}}
    @if($image)
        <img src="{{ $image }}" alt=""
             class="shrink-0 w-24 h-12 object-cover border-2 border-black"
             loading="lazy">
    @else
        <div class="shrink-0 w-24 h-12 bg-gray-200 border-2 border-black flex items-center justify-center"
             aria-hidden="true">
            <i data-lucide="image-off" class="w-4 h-4 text-gray-400"></i>
        </div>
    @endif

    {{-- Name --}}
    <span class="flex-1 min-w-0 font-black text-xs sm:text-sm uppercase text-[#0F3A52] truncate
                 group-hover:text-[#5DA9D6] transition-colors">
        {{ $name }}
    </span>

    {{-- Price --}}
    <span class="shrink-0 bg-[#FACC15] border-2 border-black px-2 py-0.5
                 font-black text-xs text-black whitespace-nowrap">
        {{ $price }}
    </span>
</a>
