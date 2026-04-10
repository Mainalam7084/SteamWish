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

                {{-- Wishlist icon --}}
                <a href="#" id="nav-wishlist"
                    class="hidden sm:flex relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 items-center justify-center nb-shadow-sm nb-hover group"
                    title="Mi Wishlist">
                    <i data-lucide="heart" class="w-4 h-4 text-[#FACC15]"></i>
                </a>

                <a href="#" id="nav-notifications"
                    class="hidden sm:flex relative w-9 h-9 bg-[#0F3A52] border-2 border-white/30 items-center justify-center nb-shadow-sm nb-hover group"
                    title="Notificaciones">
                    <i data-lucide="bell" class="w-4 h-4 text-[#FACC15]"></i>
                </a>

                {{-- Login / User --}}
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" id="nav-logout"
                            class="flex items-center gap-2 bg-[#FACC15] border-2 border-black text-black font-black text-xs sm:text-sm uppercase tracking-wider px-3 py-2 nb-shadow nb-hover nb-hover-yellow transition-all duration-100">
                            <i data-lucide="log-out" class="w-4 h-4 hidden sm:block"></i>
                            <span class="hidden sm:block">Logout</span>
                            <i data-lucide="log-out" class="w-4 h-4 sm:hidden"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" id="nav-signin"
                        class="flex items-center gap-2 bg-[#FACC15] border-2 border-black text-black font-black text-xs sm:text-sm uppercase tracking-wider px-3 py-2 nb-shadow nb-hover nb-hover-yellow transition-all duration-100">
                        <i data-lucide="log-in" class="w-4 h-4 hidden sm:block"></i>
                        <span class="hidden sm:block">Sign in</span>
                        <i data-lucide="log-in" class="w-4 h-4 sm:hidden"></i>
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
