<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SteamWish – Tu plataforma de videojuegos para wishlist, ofertas y trending games.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SteamWish – Gaming Wishlist & Deals')</title>

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    {{-- Iconos --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/animateMosaico.js', 'resources/js/interactions.js'])

    @php
        $globalThemeColor = Auth::check() ? (Auth::user()->preferences['themeColor'] ?? '#FACC15') : '#FACC15';
        $complementaryMap = [
            '#FACC15' => '#16A34A',
            '#4ADE80' => '#F472B6',
            '#F87171' => '#60A5FA',
            '#60A5FA' => '#F87171',
            '#C084FC' => '#FACC15',
            '#F472B6' => '#4ADE80',
        ];
        $globalSecondaryColor = $complementaryMap[$globalThemeColor] ?? '#16A34A';
    @endphp
    <style>
        :root {
            --theme-color: {{ $globalThemeColor }};
            --theme-secondary: {{ $globalSecondaryColor }};
        }
        
        /* Tema Primario (Sustituyendo el Amarillo #FACC15) */
        .bg-\[\#FACC15\] { background-color: var(--theme-color) !important; }
        .text-\[\#FACC15\] { color: var(--theme-color) !important; }
        .border-\[\#FACC15\] { border-color: var(--theme-color) !important; }
        .shadow-\[4px_4px_0_0_\#FACC15\] { box-shadow: 4px 4px 0 0 var(--theme-color) !important; }
        .shadow-\[8px_8px_0_0_\#FACC15\] { box-shadow: 8px 8px 0 0 var(--theme-color) !important; }
        .hover\:text-\[\#FACC15\]:hover { color: var(--theme-color) !important; }
        .hover\:bg-\[\#FACC15\]:hover { background-color: var(--theme-color) !important; }
        .group-hover\:text-\[\#FACC15\]:hover { color: var(--theme-color) !important; }
        .group-hover\:bg-\[\#FACC15\]:hover { background-color: var(--theme-color) !important; }
        
        /* Tema Secundario Complementario (Sustituyendo el Verde #16A34A del Trending) */
        .bg-\[\#16A34A\] { background-color: var(--theme-secondary) !important; }
        .text-\[\#16A34A\] { color: var(--theme-secondary) !important; }
        .border-\[\#16A34A\] { border-color: var(--theme-secondary) !important; }
        .shadow-\[4px_4px_0_0_\#16A34A\] { box-shadow: 4px 4px 0 0 var(--theme-secondary) !important; }
        
        /* Custom Scrollbar Brutalist */
        ::-webkit-scrollbar {
            width: 16px;
            height: 16px;
        }
        ::-webkit-scrollbar-track {
            background: #0F3A52;
            border-left: 3px solid black;
            border-top: 3px solid black;
        }
        ::-webkit-scrollbar-thumb {
            background-color: var(--theme-color);
            border: 3px solid black;
            border-right: none;
            border-bottom: none;
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--theme-color);
        }
    </style>
</head>

<body class="bg-[#F5F5F5] text-[#0F3A52] font-sans antialiased min-h-screen flex flex-col">

    {{-- Saltar al contenido --}}
    <a href="#main-content" class="skip-link">Ir al contenido principal</a>

    {{-- Barra superior --}}
    <div class="bg-[#0F3A52] border-b-4 border-black overflow-hidden py-1.5">
        <div class="marquee-inner text-[#FACC15] font-bold text-sm uppercase tracking-widest">
            @php
                $tickerItems = ['Main Alam', 'David Rollon', 'Geovanny Jimenez'];
            @endphp

            @for ($i = 0; $i < 4; $i++)
                @foreach ($tickerItems as $item)
                    <span class="px-8">{{ $item }}</span>
                    <span class="text-white/30">|</span>
                @endforeach
            @endfor
        </div>
    </div>

    {{-- Barra de navegación --}}
    <x-navbar />

    {{-- Contenido principal --}}
    <main id="main-content" class="flex-grow">
        @yield('content')
    </main>

    {{-- Pie de página --}}
    <footer class="w-full border-t-4 border-black bg-[#0F3A52] py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="font-black text-2xl text-white uppercase tracking-tighter">
                STEAM<span class="text-[#FACC15] drop-shadow-[1px_1px_0_#000]">WISH</span>
            </div>
            <div class="font-bold text-sm text-gray-300 uppercase">
                &copy; {{ date('Y') }} Todos los derechos reservados. Licencia MIT.
            </div>
        </div>
    </footer>

    {{-- Loader cursor --}}
    <img id="cursor-loader" src="" class="fixed z-[10000] w-20 h-20 object-cover border-4 border-black pointer-events-none hidden transition-opacity duration-100 opacity-0 shadow-[4px_4px_0_0_#FACC15] bg-[#F5F5F5]">

    {{-- Loader global --}}
    <div id="global-loader" class="fixed inset-0 z-[9999] bg-[#0F3A52]/90 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-200 opacity-0 pointer-events-none hidden">
        <div class="relative border-4 border-black shadow-[8px_8px_0_0_#FACC15] bg-white p-4 max-w-[300px] w-full mx-4 flex flex-col items-center">
            <img id="loader-gif" src="" alt="Cargando..." class="w-full h-auto border-4 border-black object-cover aspect-square bg-[#F5F5F5]">
            <h2 class="mt-4 font-black text-2xl uppercase text-center text-[#0F3A52] tracking-widest animate-pulse">Cargando...</h2>
        </div>
    </div>

    {{-- Scripts Configuration for External JS --}}
    <script>
        window.AppConfig = {
            routes: {
                login: "{{ route('auth.steam') }}",
                wishlistToggle: "{{ route('wishlist.toggle') }}",
                wishlistPreview: "{{ route('api.wishlist-preview') }}",
                notificationsPreview: "{{ route('api.notifications-preview') }}",
                notificationsIndex: "{{ route('notifications.index') }}"
            },
            isGuest: @json(auth()->guest()),
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    
    @stack('scripts')
</body>

</html>
