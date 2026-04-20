@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- HERO SECTION -->
    <div class="mb-12 w-full">
        <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#0F3A52] md:shadow-[24px_24px_0_0_#0F3A52] flex flex-col md:flex-row w-full overflow-hidden hover:-translate-y-2 hover:-translate-x-2 hover:shadow-[24px_24px_0_0_#0F3A52] md:hover:shadow-[32px_32px_0_0_#0F3A52] transition-all duration-300">
            
            <!-- Visual Side -->
            <div class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-r-8 border-black relative bg-[#7F77DD] flex items-center justify-center p-16 min-h-[300px] overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none">
                    <i data-lucide="mail" class="w-[300px] h-[300px] text-black transform rotate-12"></i>
                </div>
                <div class="relative z-10 text-center transform -rotate-3">
                    <h2 class="text-7xl md:text-8xl font-black uppercase text-white drop-shadow-[6px_6px_0_#000] leading-none">
                        HOLA
                    </h2>
                    <div class="inline-block bg-[#FACC15] text-black font-black uppercase px-6 py-2 border-4 border-black mt-4 text-2xl shadow-[6px_6px_0_0_#000]">
                        Escríbenos
                    </div>
                </div>
            </div>
            
            <!-- Content Side -->
            <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#0F3A52]">
                <h1 class="text-5xl md:text-7xl font-black uppercase text-[#FACC15] mb-6 leading-tight tracking-tighter relative z-10 drop-shadow-[4px_4px_0_#000]">
                    Contáctanos
                </h1>
                <p class="text-xl md:text-2xl font-bold text-white bg-black/30 inline-block px-4 py-2 border-2 border-dashed border-white/50 relative z-10">
                    ¿Tienes dudas, sugerencias o simplemente quieres saludar? Estamos aquí para escucharte.
                </p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTACT CARD -->
    <div class="my-24 w-full">
        <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#1D9E75] md:shadow-[24px_24px_0_0_#1D9E75] flex flex-col md:flex-row w-full overflow-hidden hover:translate-y-2 hover:translate-x-2 transition-all duration-300">
            
            <!-- Info Side -->
            <div class="w-full md:w-4/12 border-b-8 md:border-b-0 md:border-r-8 border-black bg-[#F5F5F5] p-8 md:p-12 flex flex-col justify-between">
                <div>
                    <h3 class="text-3xl font-black uppercase text-[#0F3A52] mb-8 underline decoration-8 decoration-[#1D9E75]">Información</h3>
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="bg-black p-2 border-2 border-black shadow-[4px_4px_0_0_#1D9E75]">
                                <i data-lucide="map-pin" class="w-6 h-6 text-[#1D9E75]"></i>
                            </div>
                            <div>
                                <span class="block font-black uppercase text-xs text-gray-500">Ubicación</span>
                                <span class="font-bold text-lg">Madrid, España</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-black p-2 border-2 border-black shadow-[4px_4px_0_0_#1D9E75]">
                                <i data-lucide="mail-plus" class="w-6 h-6 text-[#1D9E75]"></i>
                            </div>
                            <div>
                                <span class="block font-black uppercase text-xs text-gray-500">Email Directo</span>
                                <span class="font-bold text-lg">soporte@steamwish.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <div class="bg-[#FACC15] border-4 border-black p-4 rotate-2 shadow-[6px_6px_0_0_#000]">
                        <p class="font-black uppercase text-sm italic">Respuesta promedio: <span class="text-white bg-black px-2 ml-1">24 Horas</span></p>
                    </div>
                </div>
            </div>

            <!-- Form Side -->
            <div class="w-full md:w-8/12 p-8 md:p-16 bg-white">
                @if(session('success'))
                    <div class="bg-[#1D9E75] text-white border-4 border-black p-6 mb-8 font-black uppercase text-center shadow-[8px_8px_0_0_#000] animate-bounce">
                        ¡Mensaje Enviado con Éxito!
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                    @csrf 

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col">
                            <label for="nombre" class="font-black uppercase text-[#0F3A52] mb-2 flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i> Nombre
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required 
                                   class="border-4 border-black p-4 text-lg font-bold focus:bg-[#FACC15]/10 outline-none transition-colors
                                   @error('nombre') bg-red-50 border-red-600 @else border-black @enderror"
                                   placeholder="Tu nombre aquí...">
                            @error('nombre')
                                <span class="bg-red-600 text-white font-black text-xs uppercase px-2 py-1 mt-1 self-start border-2 border-black shadow-[2px_2px_0_0_#000]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="email" class="font-black uppercase text-[#0F3A52] mb-2 flex items-center gap-2">
                                <i data-lucide="at-sign" class="w-4 h-4"></i> Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                   class="border-4 border-black p-4 text-lg font-bold focus:bg-[#FACC15]/10 outline-none transition-colors
                                   @error('email') bg-red-50 border-red-600 @else border-black @enderror"
                                   placeholder="ejemplo@correo.com">
                            @error('email')
                                <span class="bg-red-600 text-white font-black text-xs uppercase px-2 py-1 mt-1 self-start border-2 border-black shadow-[2px_2px_0_0_#000]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label for="mensaje" class="font-black uppercase text-[#0F3A52] mb-2 flex items-center gap-2">
                            <i data-lucide="message-square" class="w-4 h-4"></i> Mensaje
                        </label>
                        <textarea name="mensaje" id="mensaje" rows="5" required 
                                  class="border-4 border-black p-4 text-lg font-bold focus:bg-[#FACC15]/10 outline-none transition-colors
                                  @error('mensaje') bg-red-50 border-red-600 @else border-black @enderror"
                                  placeholder="¿En qué podemos ayudarte?">{{ old('mensaje') }}</textarea>
                        @error('mensaje')
                            <span class="bg-red-600 text-white font-black text-xs uppercase px-2 py-1 mt-1 self-start border-2 border-black shadow-[2px_2px_0_0_#000]">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#1D9E75] text-white font-black uppercase text-2xl border-4 border-black py-6 shadow-[8px_8px_0_0_#000] hover:translate-y-1 hover:shadow-[4px_4px_0_0_#000] active:translate-y-2 active:shadow-none transition-all cursor-pointer flex items-center justify-center gap-4">
                        <i data-lucide="send" class="w-6 h-6"></i> Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection