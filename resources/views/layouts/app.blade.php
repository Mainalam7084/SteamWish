<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SteamWish – Tu plataforma de videojuegos para wishlist, ofertas y trending games.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SteamWish – Gaming Wishlist & Deals')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/animateMosaico.js'])
</head>

<body class="bg-[#F5F5F5] text-[#0F3A52] font-sans antialiased">

    {{-- Ticker Bar --}}
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

    {{-- Navbar --}}
    <x-navbar />

    {{-- Main Content --}}
    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Scripts --}}
    <script>
        lucide.createIcons();

        document.addEventListener('click', function(e) {
            const heartBtn = e.target.closest('[data-heart]');
            if (heartBtn) {
                heartBtn.classList.toggle('text-[#5DA9D6]');
                heartBtn.classList.toggle('text-[#0F3A52]');
                heartBtn.classList.add('heart-active');
                setTimeout(() => heartBtn.classList.remove('heart-active'), 300);
            }
        });
    </script>

    @stack('scripts')
    <script>
        document.addEventListener('click', function(e) {
            const wishlistBtn = e.target.closest('.wishlist-btn');
            if (wishlistBtn) {
                e.preventDefault();

                // Si no está registrado, reenviarlo a login de Steam
                @guest
                window.location.href = "{{ route('auth.steam') }}";
                return;
            @endguest

            const appid = wishlistBtn.dataset.appid;
            if (!appid) return;

            fetch("{{ route('wishlist.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        appid: appid
                    })
                })
                .then(res => res.json())
                .then(data => {
                    // Lógica visual: Si fue añadido cambiar color a Amarillo, de lo contrario a Blanco/Azul
                    const btns = document.querySelectorAll(`.wishlist-btn[data-appid="${appid}"]`);
                    btns.forEach(btn => {
                        if (data.status === 'added') {
                            // Pon lógica visual de guardado / icono de llenado
                            btn.classList.add('bg-[#FACC15]', 'text-black');
                        } else {
                            // Pon lógica visual de vacío
                            btn.classList.remove('bg-[#FACC15]', 'text-black');
                        }
                    });
                });
        }
        });
    </script>
</body>

</html>
