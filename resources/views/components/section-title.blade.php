@props([
    'title'   => 'Section Title',
    'icon'    => 'zap',
    'color'   => 'primary',    {{-- primary | secondary | accent | dark | neutral --}}
    'tag'     => null,
])

@php
    /*
     * Paleta SteamWish:
     *   primary   → azul claro  #5DA9D6
     *   secondary → azul oscuro #0F3A52
     *   accent    → amarillo    #FACC15
     *   dark      → negro       #000000
     *   neutral   → gris        #E5E7EB
     *
     * Alias de compatibilidad con el HTML existente:
     *   yellow  → accent
     *   blue    → primary
     *   green   → accent (fallback)
     *   pink    → secondary
     *   purple  → secondary
     */
    $colorMap = [
        'primary'   => ['bg' => 'bg-[#5DA9D6]', 'text' => 'text-white',      'shadow' => 'nb-shadow-primary'],
        'secondary' => ['bg' => 'bg-[#0F3A52]', 'text' => 'text-white',      'shadow' => 'nb-shadow-dark'],
        'accent'    => ['bg' => 'bg-[#FACC15]', 'text' => 'text-black',      'shadow' => 'nb-shadow-accent'],
        'dark'      => ['bg' => 'bg-black',      'text' => 'text-white',      'shadow' => 'nb-shadow'],
        'neutral'   => ['bg' => 'bg-[#E5E7EB]', 'text' => 'text-[#0F3A52]', 'shadow' => 'nb-shadow'],
        // Compatibilidad
        'yellow'    => ['bg' => 'bg-[#FACC15]', 'text' => 'text-black',      'shadow' => 'nb-shadow-accent'],
        'blue'      => ['bg' => 'bg-[#5DA9D6]', 'text' => 'text-white',      'shadow' => 'nb-shadow-primary'],
        'green'     => ['bg' => 'bg-[#5DA9D6]', 'text' => 'text-white',      'shadow' => 'nb-shadow-primary'],
        'pink'      => ['bg' => 'bg-[#0F3A52]', 'text' => 'text-white',      'shadow' => 'nb-shadow-dark'],
        'purple'    => ['bg' => 'bg-[#0F3A52]', 'text' => 'text-white',      'shadow' => 'nb-shadow-dark'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="flex items-center gap-3 mb-6">
    <!-- Icon pill -->
    <div class="{{ $c['bg'] }} {{ $c['text'] }} {{ $c['shadow'] }} border-4 border-black flex items-center justify-center w-11 h-11 shrink-0">
        <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
    </div>

    <!-- Title -->
    <div>
        <h2 class="font-black text-[#0F3A52] text-xl sm:text-2xl lg:text-3xl uppercase tracking-tight leading-none">
            {{ $title }}
        </h2>
        @if($tag)
        <span class="inline-block mt-1 {{ $c['bg'] }} {{ $c['text'] }} text-[10px] font-black uppercase tracking-widest px-2 py-0.5 border-2 border-black">
            {{ $tag }}
        </span>
        @endif
    </div>

    <!-- Decorative line -->
    <div class="flex-1 h-1 {{ $c['bg'] }} border-t-2 border-b-2 border-black ml-2 hidden sm:block"></div>
</div>
