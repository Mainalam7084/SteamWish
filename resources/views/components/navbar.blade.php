<nav id="navbar" class="bg-[#0F3A52] border-b-4 border-black sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-2 sm:gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" id="nav-logo"
                class="flex items-center shrink-0 group transition-all duration-150">
                <div class="relative bg-white border-2 border-black rounded-full p-1 transition-all duration-150">
                    <img src="{{ asset('img/SteamWishLogo.png') }}" alt="SteamWish"
                        class="h-10 w-auto sm:h-14 object-contain rounded-full">
                </div>
            </a>

            {{-- Buscador --}}
            <form method="GET" action="{{ route('search') }}" class="flex-1">
                <div class="relative flex items-center">
                    <div class="absolute left-3 text-[#0F3A52] pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input id="nav-search" type="text" name="q" value="{{ request('q') }}"
                        placeholder="Buscar juegos, DLCs, géneros..."
                        class="w-full bg-white border-2 border-black text-[#0F3A52] placeholder-gray-400 pl-10 pr-4 py-2 text-sm font-mono nb-shadow focus:outline-none focus:border-[#5DA9D6] focus:shadow-[4px_4px_0px_#5DA9D6] transition-all duration-100"
                        autocomplete="off">
                    <button id="nav-search-btn" type="submit"
                        class="absolute right-0 h-full px-2 sm:px-4 bg-[#FACC15] border-l-2 border-black text-black font-bold text-xs uppercase tracking-wider hover:bg-white transition-colors duration-100">
                        <span class="hidden sm:inline">Buscar</span>
                        <i data-lucide="search" class="w-3 h-3 sm:hidden"></i>
                    </button>
                </div>
            </form>

            {{-- Menú escritorio --}}
            <div class="hidden md:flex items-center gap-2 shrink-0">

                {{-- Enlaces --}}
                <nav class="flex items-center gap-1">
                    <a href="{{ route('home') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('home') ? 'text-[#FACC15]' : '' }}">
                        Inicio
                    </a>
                    <a href="{{ route('about') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('about') ? 'text-[#FACC15]' : '' }}">
                        Nosotros
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('contact') ? 'text-[#FACC15]' : '' }}">
                        Contacto
                    </a>
                </nav>

                {{-- Dropdown Wishlist --}}
                @auth
                    <div class="relative group h-full flex items-center" id="nav-wishlist-container">
                        <a href="{{ route('wishlist.index') }}" id="nav-wishlist"
                            class="relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 flex items-center justify-center nb-shadow-sm transition-all duration-100 group-hover:bg-[#FACC15] group-hover:border-black text-[#FACC15] group-hover:!text-black [&>svg]:group-hover:!stroke-black"
                            title="Mi Wishlist">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                        </a>

                        {{-- Puente hover --}}
                        <div class="absolute right-0 top-full pt-4 hidden group-hover:block z-50">
                            <div class="w-72 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] flex flex-col pt-2"
                                id="nav-wishlist-dropdown">
                                <div class="px-4 pb-2 border-b-2 border-black flex items-center justify-between">
                                    <span class="font-black uppercase text-[#0F3A52] text-sm">Agregados Recién</span>
                                </div>

                                <div id="nav-wishlist-items" class="flex flex-col">
                                    <div class="p-4 text-center text-xs font-bold text-gray-400" id="nav-wishlist-loading">
                                        Cargando...
                                    </div>
                                </div>

                                <a href="{{ route('wishlist.index') }}"
                                    class="block px-4 py-3 bg-[#F5F5F5] text-center text-[#0F3A52] font-black hover:bg-[#FACC15] border-t-2 border-black transition-colors uppercase text-xs">
                                    Ver toda la lista
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Dropdown Notificaciones --}}
                @auth
                    @php $unreadCount = Auth::user()->unreadNotificationsCount(); @endphp
                    <div class="relative group h-full flex items-center" id="nav-notifications-container">
                        <a href="{{ route('notifications.index') }}" id="nav-notifications"
                            class="relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 flex items-center justify-center nb-shadow-sm transition-all duration-100 group-hover:bg-[#FACC15] group-hover:border-black text-[#FACC15] group-hover:!text-black [&>svg]:group-hover:!stroke-black"
                            title="Notificaciones">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                            <span id="nav-notif-badge"
                                class="absolute -top-1.5 -right-1.5 min-w-[1rem] h-4 px-0.5 bg-red-500 border border-black text-white text-[9px] font-black items-center justify-center rounded-none group-hover:!text-white transition-all duration-200 {{ $unreadCount > 0 ? 'flex' : 'hidden' }}">
                                {{ $unreadCount > 9 ? '9+' : ($unreadCount > 0 ? $unreadCount : '') }}
                            </span>
                        </a>

                        {{-- Invisible bridge wrapper for hover --}}
                        <div class="absolute right-0 top-full pt-4 hidden group-hover:block z-50">
                            <div class="w-80 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] flex flex-col pt-2"
                                id="nav-notifications-dropdown">
                                <div class="px-4 pb-2 border-b-2 border-black flex items-center justify-between">
                                    <span class="font-black uppercase text-[#0F3A52] text-sm">Notificaciones</span>
                                    <span id="nav-notif-unread-label" class="text-[10px] font-bold text-gray-400"></span>
                                </div>

                                <div id="nav-notifications-items" class="flex flex-col">
                                    <div class="p-4 text-center text-xs font-bold text-gray-400" id="nav-notif-loading">
                                        Cargando...
                                    </div>
                                </div>

                                <a href="{{ route('notifications.index') }}"
                                    class="block px-4 py-3 bg-[#F5F5F5] text-center text-[#0F3A52] font-black hover:bg-[#FACC15] border-t-2 border-black transition-colors uppercase text-xs">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Campana (No logueados) --}}
                @guest
                    <a href="{{ route('auth.steam') }}"
                        class="relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 flex items-center justify-center nb-shadow-sm nb-hover group"
                        title="Inicia sesión para ver notificaciones">
                        <i data-lucide="bell" class="w-4 h-4 text-[#FACC15]"></i>
                    </a>
                @endguest

                {{-- Usuario --}}
                @auth
                    <div class="relative group">
                        <button aria-haspopup="true" aria-label="Menú de usuario: {{ Auth::user()->username }}"
                            class="flex items-center gap-2 bg-[#FACC15] border-2 border-black text-black font-black text-sm tracking-wider px-2 py-1 shadow-[4px_4px_0px_0px_black] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_black] active:translate-x-1 active:translate-y-1 active:shadow-[0px_0px_0px_0px_black] transition-all duration-100 h-full">
                            <img src="{{ Auth::user()->avatar }}" alt="" class="w-6 h-6 border border-black"
                                aria-hidden="true">
                            <span class="truncate max-w-[100px]">{{ Auth::user()->username }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4" aria-hidden="true"></i>
                        </button>

                        {{-- Invisible bridge wrapper for hover --}}
                        <div class="absolute right-0 top-full pt-2 hidden group-hover:block z-50">
                            <div class="w-48 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] flex flex-col">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-[#0F3A52] font-bold hover:bg-[#FACC15] border-b-2 border-black transition-colors">Panel
                                    de Usuario</a>
                                <a href="{{ route('wishlist.index') }}"
                                    class="block px-4 py-2 text-[#0F3A52] font-bold hover:bg-[#FACC15] border-b-2 border-black transition-colors">Lista
                                    de Deseos</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-red-600 font-black hover:bg-black hover:text-white transition-colors">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.steam') }}" id="nav-signin"
                        class="flex items-center gap-2 bg-[#FACC15] text-black font-black text-base uppercase tracking-wider px-4 py-2 border-2 border-black shadow-[4px_4px_0px_0px_black] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_black] active:translate-x-1 active:translate-y-1 active:shadow-[0px_0px_0px_0px_black] transition-all duration-150">
                        <i data-lucide="gamepad-2" class="w-5 h-5"></i>
                        Login con Steam
                    </a>
                @endauth

            </div>

            {{-- Menú móvil --}}
            <button id="mobile-menu-btn"
                class="flex md:hidden items-center justify-center w-10 h-10 bg-[#0F3A52] border-2 border-white/30 shrink-0 hover:border-[#FACC15] transition-colors duration-100"
                aria-label="Abrir menú" aria-expanded="false" aria-controls="mobile-menu">
                <i data-lucide="menu" class="w-5 h-5 text-white" id="mobile-menu-icon-open"></i>
                <i data-lucide="x" class="w-5 h-5 text-[#FACC15] hidden" id="mobile-menu-icon-close"></i>
            </button>

        </div>
    </div>

    {{-- Panel móvil --}}
    <div id="mobile-menu" class="hidden md:hidden border-t-2 border-white/10 bg-[#0F3A52]">
        <div class="px-4 py-2">

            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10 {{ request()->routeIs('home') ? '!text-[#FACC15]' : '' }}">
                <i data-lucide="home" class="w-4 h-4"></i>
                Inicio
            </a>
            <a href="{{ route('about') }}"
                class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10 {{ request()->routeIs('about') ? '!text-[#FACC15]' : '' }}">
                <i data-lucide="info" class="w-4 h-4"></i>
                Nosotros
            </a>
            <a href="{{ route('contact') }}"
                class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10 {{ request()->routeIs('contact') ? '!text-[#FACC15]' : '' }}">
                <i data-lucide="mail" class="w-4 h-4"></i>
                Contacto
            </a>

            @auth
                <div class="mt-2 pt-2 border-t-2 border-white/20">
                    <div class="flex items-center gap-3 px-3 py-2 mb-1">
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->username }}"
                            class="w-10 h-10 border-2 border-[#FACC15]">
                        <span class="text-white font-black text-sm truncate">{{ Auth::user()->username }}</span>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Panel
                    </a>
                    <a href="{{ route('wishlist.index') }}"
                        class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        Mi Wishlist
                    </a>
                    <a href="{{ route('notifications.index') }}"
                        class="flex items-center gap-3 px-3 py-3 text-white/80 hover:text-[#FACC15] hover:bg-white/5 text-sm font-bold uppercase tracking-wider transition-colors duration-100 border-b border-white/10">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        Notificaciones
                        @if (isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="ml-auto bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 border border-black">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-3 text-red-400 hover:text-red-300 hover:bg-white/5 text-sm font-black uppercase tracking-wider transition-colors duration-100">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-2 pt-3 border-t-2 border-white/20 pb-2">
                    <a href="{{ route('auth.steam') }}"
                        class="flex items-center justify-center gap-2 bg-[#FACC15] text-black font-black text-sm uppercase tracking-wider px-4 py-3 border-2 border-black shadow-[4px_4px_0px_0px_black] transition-all duration-150">
                        <i data-lucide="gamepad-2" class="w-5 h-5"></i>
                        Login con Steam
                    </a>
                </div>
            @endauth

        </div>
    </div>

    <script>
        (function() {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            var iconOpen = document.getElementById('mobile-menu-icon-open');
            var iconClose = document.getElementById('mobile-menu-icon-close');
            if (!btn || !menu) return;
            btn.addEventListener('click', function() {
                var isOpen = !menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden', !isOpen);
                iconClose.classList.toggle('hidden', isOpen);
                btn.setAttribute('aria-expanded', String(!isOpen));
            });
        })();
    </script>
</nav>
