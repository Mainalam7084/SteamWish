@extends('layouts.app')

@section('title', 'Login – SteamWish')

@section('content')

    <div class="min-h-[80vh] flex items-center justify-center px-4 py-16">

        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <img src="{{ asset('img/SteamWishLogo.png') }}" alt="SteamWish" class="h-16 w-auto mx-auto object-contain">
                <h1 class="font-black text-[#0F3A52] text-2xl uppercase tracking-tight mt-4">
                    Accede a SteamWish
                </h1>
                <p class="text-gray-400 text-sm mt-2">
                    Conecta tu cuenta de Steam para gestionar tu wishlist y recibir alertas de precios.
                </p>
            </div>

            {{-- Login Card --}}
            <div class="bg-white border-4 border-black nb-shadow-lg p-8 flex flex-col gap-6">

                {{-- Info notice --}}
                <div class="bg-[#EFF6FF] border-2 border-[#5DA9D6] p-3 flex items-start gap-3">
                    <i data-lucide="info" class="w-4 h-4 text-[#5DA9D6] shrink-0 mt-0.5"></i>
                    <p class="text-[#0F3A52] text-xs leading-relaxed">
                        SteamWish usa <strong>Steam OpenID</strong>. No almacenamos tu contraseña.
                        Serás redirigido a la página oficial de Steam para autenticarte.
                    </p>
                </div>

                {{-- Flash messages --}}
                @if (session('info'))
                    <div class="bg-[#FACC15] border-2 border-black p-3 text-sm font-bold text-black">
                        {{ session('info') }}
                    </div>
                @endif

                {{-- Steam Login Button --}}
                <a href="{{ route('auth.steam') }}" id="login-steam-btn"
                    class="flex items-center justify-center gap-3 bg-[#0F3A52] text-white border-4 border-black font-black text-base uppercase tracking-wider px-6 py-4 nb-shadow hover:bg-[#5DA9D6] hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_black] transition-all duration-100 w-full text-center">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                    Login with Steam
                </a>

                <div class="border-t-2 border-gray-100 pt-4 text-center">
                    <a href="{{ route('home') }}"
                        class="text-gray-400 text-xs hover:text-[#5DA9D6] transition-colors font-mono">
                        ← Volver al inicio sin conectar
                    </a>
                </div>

            </div>

            <p class="text-center text-gray-300 text-xs mt-4 font-mono">
                Al conectar, aceptas nuestros términos de uso.
            </p>

        </div>

    </div>

@endsection
