@props(['game'])

@php
    $appid    = $game['appid']            ?? '';
    $name     = $game['name']             ?? 'Unknown Game';
    $image    = $game['image']            ?? null;
    $discount = $game['discount_percent'] ?? 0;

    if ($game['is_free'] ?? false) {
        $price = 'Free';
    } elseif (isset($game['price']) && $game['price'] !== null) {
        $price = number_format((float) $game['price'], 2) . '€';
    } else {
        $price = '—';
    }
@endphp

<a href="/game?appid={{ $appid }}"
   class="flex items-center gap-4 bg-white border-4 border-black p-3
          shadow-[4px_4px_0_0_#16A34A] hover:shadow-[6px_6px_0_0_#5DA9D6]
          hover:-translate-x-1 hover:-translate-y-1 transition-all group">

    {{-- Thumbnail --}}
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}"
             class="shrink-0 w-16 h-8 object-cover border-2 border-black">
    @else
        <div class="shrink-0 w-16 h-8 bg-gray-200 border-2 border-black flex items-center justify-center">
            <i data-lucide="image-off" class="w-4 h-4 text-gray-400"></i>
        </div>
    @endif

    {{-- Name --}}
    <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate
                 group-hover:text-[#5DA9D6] transition-colors">
        {{ $name }}
    </span>

    {{-- Discount badge or price --}}
    @if($discount > 0)
        <span class="shrink-0 bg-[#16A34A] border-2 border-black px-2 py-0.5
                     font-black text-xs text-white">
            -{{ $discount }}%
        </span>
    @else
        <span class="shrink-0 bg-[#FACC15] border-2 border-black px-2 py-0.5
                     font-black text-xs text-black">
            {{ $price }}
        </span>
    @endif
</a>
