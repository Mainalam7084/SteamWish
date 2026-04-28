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

    {{-- Global Loader --}}
    <div id="global-loader" class="fixed inset-0 z-[9999] bg-[#0F3A52]/90 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-200 opacity-0 pointer-events-none hidden">
        <div class="relative border-4 border-black shadow-[8px_8px_0_0_#FACC15] bg-white p-4 max-w-[300px] w-full mx-4 flex flex-col items-center">
            <img id="loader-gif" src="" alt="Cargando..." class="w-full h-auto border-4 border-black object-cover aspect-square bg-[#F5F5F5]">
            <h2 class="mt-4 font-black text-2xl uppercase text-center text-[#0F3A52] tracking-widest animate-pulse">Cargando...</h2>
        </div>
    </div>

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
                    // Update ALL wishlist buttons with this appid (heart + text buttons)
                    const btns = document.querySelectorAll(`.wishlist-btn[data-appid="${appid}"]`);
                    btns.forEach(btn => {
                        const textSpan = btn.querySelector('span');

                        if (data.status === 'added') {
                            btn.classList.add('bg-[#FACC15]', 'text-black');
                            btn.classList.remove('bg-white', 'text-[#0F3A52]', 'hover:bg-[#5DA9D6]', 'hover:text-white');
                            if (textSpan) textSpan.textContent = 'Saved';
                        } else {
                            btn.classList.remove('bg-[#FACC15]', 'text-black');
                            btn.classList.add('bg-white', 'text-[#0F3A52]');
                            if (textSpan) textSpan.textContent = 'Add to Wishlist';
                            // Notify wishlist page to remove the card
                            btn.dispatchEvent(new CustomEvent('wishlist:removed', { bubbles: true }));
                        }
                    });
                });
        }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navWishlistContainer = document.getElementById('nav-wishlist-container');
            const navWishlistItems = document.getElementById('nav-wishlist-items');
            
            if (navWishlistContainer && navWishlistItems) {
                let previewLoaded = false;

                navWishlistContainer.addEventListener('mouseenter', () => {
                    if (previewLoaded) return;
                    previewLoaded = true; // Solo cargar una vez por sesión de pagina

                    fetch('{{ route("api.wishlist-preview") }}')
                        .then(res => res.ok ? res.json() : [])
                        .then(games => {
                            if (games.length === 0) {
                                navWishlistItems.innerHTML = `
                                    <div class="p-4 text-center">
                                        <i data-lucide="ghost" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        <p class="text-xs font-bold text-gray-400">Aún no has guardado ningún juego.</p>
                                    </div>
                                `;
                            } else {
                                navWishlistItems.innerHTML = games.map(g => `
                                    <a href="/game?appid=${g.appid}" class="flex items-center gap-3 p-3 border-b-2 border-black hover:bg-[#FACC15] group transition-colors">
                                        <img src="${g.image}" alt="${g.name}" class="w-16 h-8 object-cover border-2 border-black shrink-0">
                                        <h4 class="font-black text-[10px] uppercase text-[#0F3A52] line-clamp-2">${g.name}</h4>
                                    </a>
                                `).join('');
                            }
                            if (window.lucide) {
                                lucide.createIcons();
                            }
                        })
                        .catch(err => {
                            navWishlistItems.innerHTML = `
                                <div class="p-4 text-center">
                                    <p class="text-xs font-bold text-red-500">Error al cargar.</p>
                                </div>
                            `;
                        });
                });
            }

            // ── Notifications dropdown ──────────────────────────────────────────
            const navNotifContainer = document.getElementById('nav-notifications-container');
            const navNotifItems     = document.getElementById('nav-notifications-items');
            const navNotifLabel     = document.getElementById('nav-notif-unread-label');
            const navNotifBadge     = document.getElementById('nav-notif-badge');

            if (navNotifContainer && navNotifItems) {
                let notifLoaded = false;

                navNotifContainer.addEventListener('mouseenter', () => {
                    if (notifLoaded) return;
                    notifLoaded = true;

                    @auth
                    fetch('{{ route("api.notifications-preview") }}')
                        .then(res => res.ok ? res.json() : { notifications: [], unread: 0 })
                        .then(({ notifications, unread }) => {
                            // Update badge
                            if (navNotifBadge) {
                                if (unread > 0) {
                                    navNotifBadge.textContent = unread > 9 ? '9+' : unread;
                                    navNotifBadge.style.display = 'flex';
                                } else {
                                    navNotifBadge.style.display = 'none';
                                }
                            }
                            if (navNotifLabel) {
                                navNotifLabel.textContent = unread > 0 ? `${unread} sin leer` : 'Todo leído';
                            }

                            if (notifications.length === 0) {
                                navNotifItems.innerHTML = `
                                    <div class="p-4 text-center">
                                        <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        <p class="text-xs font-bold text-gray-400">Sin notificaciones aún.</p>
                                    </div>
                                `;
                            } else {
                                navNotifItems.innerHTML = notifications.map(n => `
                                    <a href="{{ route('notifications.index') }}"
                                       class="flex items-start gap-3 p-3 border-b-2 border-black hover:bg-[#FACC15] transition-colors ${n.is_unread ? 'bg-yellow-50' : ''}">
                                        ${n.game_image ? `<img src="${n.game_image}" alt="${n.game_name}" class="w-14 h-7 object-cover border-2 border-black shrink-0">` : ''}
                                        <div class="flex-1 min-w-0">
                                            <p class="font-black text-[10px] uppercase text-[#0F3A52] truncate">${n.game_name}</p>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <span class="text-gray-400 line-through text-[9px]">${n.old_price}</span>
                                                <span class="font-black text-[11px] text-[#16A34A]">${n.new_price}</span>
                                                ${n.discount_percent > 0 ? `<span class="bg-[#FACC15] border border-black text-[8px] font-black px-1">-${n.discount_percent}%</span>` : ''}
                                            </div>
                                            <p class="text-[9px] text-gray-400 mt-0.5">${n.created_at}</p>
                                        </div>
                                        ${n.is_unread ? '<span class="w-2 h-2 bg-red-500 border border-black shrink-0 mt-1"></span>' : ''}
                                    </a>
                                `).join('');
                            }
                            if (window.lucide) lucide.createIcons();
                        })
                        .catch(() => {
                            navNotifItems.innerHTML = `<div class="p-4 text-center"><p class="text-xs font-bold text-red-500">Error al cargar.</p></div>`;
                        });
                    @endauth
                });
            }
        });

        // ── Global Loading Screen ───────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('global-loader');
            const loaderGif = document.getElementById('loader-gif');
            const totalGifs = 7; // Tenemos 0.gif a 6.gif en public/gifs

            // Pre-cargar GIFs en segundo plano para que el navegador no cancele
            // su descarga al cambiar de página (location.href cancela peticiones pendientes).
            const preloadedGifs = [];
            for (let i = 0; i < totalGifs; i++) {
                const img = new Image();
                img.src = `/gifs/${i}.gif`;
                preloadedGifs.push(img);
            }

            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;
                
                const href = link.getAttribute('href');
                
                // Ignorar enlaces sin href, anclas, targets _blank, javascript, o si se hace click derecho/con control
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.getAttribute('target') === '_blank' || link.hasAttribute('download')) {
                    return;
                }

                if (e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) {
                    return;
                }

                // Prevenir navegación por defecto para mostrar el loader
                e.preventDefault();
                
                const randomGif = Math.floor(Math.random() * totalGifs);
                // Usamos el src pre-cargado para asegurar que ya está en caché
                loaderGif.src = preloadedGifs[randomGif].src;
                
                loader.classList.remove('hidden');
                // Timeout mínimo para que el transition de CSS funcione
                setTimeout(() => {
                    loader.classList.remove('opacity-0', 'pointer-events-none');
                    loader.classList.add('opacity-100');
                }, 10);

                // Navegar
                setTimeout(() => {
                    window.location.href = link.href;
                }, 150); 
            });
            
            // Ocultar si el usuario usa el botón Atrás del navegador (bfcache)
            window.addEventListener('pageshow', (e) => {
                if (e.persisted) {
                    loader.classList.add('hidden', 'opacity-0', 'pointer-events-none');
                    loader.classList.remove('opacity-100');
                }
            });
        });
    </script>
</body>

</html>
