<nav id="navbar" class="bg-[#0F3A52] border-b-4 border-black sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" id="nav-logo" class="flex items-center shrink-0 group">
                <img src="{{ asset('img/SteamWishLogo.png') }}" alt="SteamWish"
                    class="h-15 w-auto object-contain rounded-full group-hover:scale-105 transition-transform duration-150">
            </a>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('search') }}" class="flex-1 max-w-xl">
                <div class="relative flex items-center">
                    <div class="absolute left-3 text-[#0F3A52] pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input id="nav-search" type="text" name="q" value="{{ request('q') }}"
                        placeholder="Buscar juegos, DLCs, géneros..."
                        class="w-full bg-white border-2 border-black text-[#0F3A52] placeholder-gray-400 pl-10 pr-4 py-2 text-sm font-mono nb-shadow focus:outline-none focus:border-[#5DA9D6] focus:shadow-[4px_4px_0px_#5DA9D6] transition-all duration-100"
                        autocomplete="off">
                    <button id="nav-search-btn" type="submit"
                        class="absolute right-0 h-full px-4 bg-[#FACC15] border-l-2 border-black text-black font-bold text-xs uppercase tracking-wider hover:bg-white transition-colors duration-100">
                        Buscar
                    </button>
                </div>
            </form>

            {{-- Navigation Links + Icons + CTA --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Desktop Nav Links --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('home') ? 'text-[#FACC15]' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('about') ? 'text-[#FACC15]' : '' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-3 py-1.5 text-white/80 hover:text-[#FACC15] text-xs font-bold uppercase tracking-wider transition-colors duration-100 {{ request()->routeIs('contact') ? 'text-[#FACC15]' : '' }}">
                        Contact
                    </a>
                </nav>

                {{-- Wishlist icon & Dropdown --}}
                @auth
                <div class="relative group hidden sm:flex h-full items-center" id="nav-wishlist-container">
                    <a href="{{ route('wishlist.index') }}" id="nav-wishlist"
                        class="relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 flex items-center justify-center nb-shadow-sm transition-colors duration-100 group-hover:bg-[#FACC15] group-hover:border-black"
                        title="Mi Wishlist">
                        <i data-lucide="heart" class="w-4 h-4 text-[#FACC15] group-hover:text-black"></i>
                    </a>

                    {{-- Invisible bridge wrapper for hover --}}
                    <div class="absolute right-0 top-full pt-4 hidden group-hover:block z-50">
                        <div class="w-72 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52] flex flex-col pt-2" id="nav-wishlist-dropdown">
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

                <a href="#" id="nav-notifications"
                    class="hidden sm:flex relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 items-center justify-center nb-shadow-sm nb-hover group"
                    title="Notificaciones">
                    <i data-lucide="bell" class="w-4 h-4 text-[#FACC15]"></i>
                </a>

                {{-- Login / User --}}
                @auth
                    <div class="relative group">
                        <button
                            class="flex items-center gap-2 bg-[#FACC15] border-2 border-black text-black font-black text-xs sm:text-sm tracking-wider px-2 py-1 nb-shadow nb-hover nb-hover-yellow transition-all duration-100 h-full">
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->username }}"
                                class="w-6 h-6 border border-black">
                            <span class="hidden sm:block truncate max-w-[100px]">{{ Auth::user()->username }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>

                        {{-- Invisible bridge wrapper for hover --}}
                        <div class="absolute right-0 top-full pt-2 hidden group-hover:block z-50">
                            <div class="w-48 bg-white border-4 border-black nb-shadow flex flex-col">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-[#0F3A52] font-bold hover:bg-[#FACC15] border-b-2 border-black transition-colors">Dashboard</a>
                                <a href="{{ route('wishlist.index') }}"
                                    class="block px-4 py-2 text-[#0F3A52] font-bold hover:bg-[#FACC15] border-b-2 border-black transition-colors">My
                                    Wishlist</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-red-600 font-black hover:bg-black hover:text-white transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.steam') }}" id="nav-signin"
                        class="flex items-center gap-2 bg-[#FACC15] text-black font-black text-xs sm:text-base uppercase tracking-wider px-4 py-2 border-2 border-black shadow-[4px_4px_0px_0px_black] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_black] active:translate-x-1 active:translate-y-1 active:shadow-[0px_0px_0px_0px_black] transition-all duration-150">
                        <i data-lucide="gamepad-2" class="w-5 h-5 hidden sm:block"></i>
                        <span class="hidden sm:block">Login con Steam</span>
                        <i data-lucide="log-in" class="w-5 h-5 sm:hidden"></i>
                    </a>
                @endauth

            </div>
        </div>
    </div>

    {{-- Mobile Search --}}
    <div class="sm:hidden border-t-2 border-white/10 px-4 py-2">
        <form method="GET" action="{{ route('search') }}">
            <div class="relative flex items-center">
                <div class="absolute left-3 text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input id="nav-search-mobile" type="text" name="q" value="{{ request('q') }}"
                    placeholder="Buscar juegos..."
                    class="w-full bg-white border-2 border-black text-[#0F3A52] placeholder-gray-400 pl-9 pr-4 py-1.5 text-sm font-mono focus:outline-none focus:border-[#5DA9D6] transition-colors">
            </div>
        </form>
    </div>
</nav>
