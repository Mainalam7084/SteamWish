@props([
    'variant'      => 'primary',
    'size'         => 'md',
    'icon'         => null,
    'iconPosition' => 'left',
    'id'           => null,
    'type'         => 'button',
    'href'         => null,
])

@php
    $variants = [
        'primary'   => 'bg-[#5DA9D6] text-white border-black hover:bg-[#0F3A52] hover:text-white nb-shadow',
        'secondary' => 'bg-[#0F3A52] text-white border-black hover:bg-[#5DA9D6] hover:text-white nb-shadow',
        'danger'    => 'bg-[#DC2626] text-white border-black hover:brightness-110 nb-shadow',
        'ghost'     => 'bg-transparent text-[#0F3A52] border-[#0F3A52] hover:bg-[#0F3A52] hover:text-white nb-shadow-sm',
    ];
    $sizes = [
        'sm' => 'text-xs px-3 py-1.5 gap-1.5',
        'md' => 'text-sm px-4 py-2 gap-2',
        'lg' => 'text-base px-6 py-3 gap-2.5',
    ];

    $base  = 'inline-flex items-center font-black uppercase tracking-wider border-4 transition-all duration-100 cursor-pointer group hover:translate-x-[-2px] hover:translate-y-[-2px]';
    $class = "$base " . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
    $tag   = $href ? 'a' : 'button';
    $attrs = array_filter(['href' => $href, 'type' => $href ? null : $type, 'id' => $id]);
@endphp

<{{ $tag }} {{ $attributes->merge(array_merge($attrs, ['class' => $class])) }}>
    @if ($icon && $iconPosition === 'left')
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
    @endif

    {{ $slot }}

    @if ($icon && $iconPosition === 'right')
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0 group-hover:translate-x-1 transition-transform duration-100"></i>
    @endif
</{{ $tag }}>
