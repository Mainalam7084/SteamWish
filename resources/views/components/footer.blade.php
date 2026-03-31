<footer class="bg-[#0F3A52] border-t-4 border-black mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Brand --}}
            <div>
                <div class="mb-4">
                    <img src="{{ asset('img/SteamWishLogo.png') }}" alt="SteamWish" class="h-12 w-auto object-contain">
                </div>
                <p class="text-blue-200 text-sm leading-relaxed">
                    Tu plataforma definitiva para rastrear juegos, descubrir ofertas y gestionar tu wishlist.
                </p>
            </div>

            {{-- Navigation Links --}}
            <div>
                <h3 class="font-black text-[#FACC15] uppercase tracking-wider mb-4 border-b-2 border-[#FACC15] pb-2">
                    Explorar
                </h3>
                <ul class="space-y-2 text-sm text-blue-200">
                    <li><a href="{{ route('home') }}" class="hover:text-[#FACC15] transition-colors">Inicio</a></li>
                    <li><a href="{{ route('search') }}" class="hover:text-[#FACC15] transition-colors">Buscar juegos</a>
                    </li>
                    {{-- <li><a href="{{ route('about') }}" class="hover:text-[#FACC15] transition-colors">Sobre
                            SteamWish</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#FACC15] transition-colors">Contacto</a> --}}
                    </li>
                </ul>
            </div>

            {{-- Social --}}
            <div>
                <h3 class="font-black text-[#5DA9D6] uppercase tracking-wider mb-4 border-b-2 border-[#5DA9D6] pb-2">
                    Conectar
                </h3>
                <div class="flex gap-3">
                    <a href="#" id="footer-github"
                        class="w-10 h-10 bg-white border-2 border-white/30 text-white flex items-center justify-center nb-shadow-sm nb-hover hover:border-[#FACC15]">
                        <img src="{{ asset('icons/github.png') }}" alt="GitHub" class="w-5 h-5 object-contain">
                    </a>
                </div>
            </div>

        </div>

        <div
            class="border-t-2 border-white/10 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-blue-200">
            <span>&copy; {{ date('Y') }} SteamWish. Todos los derechos reservados.</span>
            <span class="font-mono text-[#FACC15]">// Built with Laravel &amp; Steam API</span>
        </div>
    </div>
</footer>
