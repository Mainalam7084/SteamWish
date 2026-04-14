@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    
    <div class="border-4 border-black shadow-[8px_8px_0_0_#0F3A52] p-8 bg-white">
        <h1 class="text-4xl font-black uppercase text-[#0F3A52] mb-6">Contáctanos</h1>
        <p class="mb-8 font-bold text-lg">¿Tienes alguna duda? Escríbenos un mensaje.</p>

        @if(session('success'))
            <div class="bg-green-200 border-4 border-black p-4 mb-6 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf 

            <div class="flex flex-col">
                <label for="nombre" class="font-bold mb-2">Tu Nombre:</label>
                <input type="text" name="nombre" id="nombre" required 
                       class="border-4 border-black p-3 focus:outline-none focus:ring-2 focus:ring-[#0F3A52]
                       @error('nombre') border-red-600 @else border-black @enderror">
                @error('nombre')
                <span class="text-red-600 font-blod text-sm mt-1">{{ $message }}</span>
                @enderror
            
            </div>

            <div class="flex flex-col">
                <label for="email" class="font-bold mb-2">Tu Correo Electrónico:</label>
                <input type="email" name="email" id="email" required 
                       class="border-4 border-black p-3 focus:outline-none focus:ring-2 focus:ring-[#0F3A52]
                       @error('email') border-red-600 @else border-black @enderror">
                
                @error('email')
                    <span class="text-red-600 font-bold text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="mensaje" class="font-bold mb-2">Mensaje:</label>
                <textarea name="mensaje" id="mensaje" rows="5" required 
                          class="border-4 border-black p-3 focus:outline-none focus:ring-2 focus:ring-[#0F3A52]
                          @error('mensaje') border-red-600 @else border-black @enderror">{{ old('mensaje') }}</textarea>
                
                @error('mensaje')
                    <span class="text-red-600 font-bold text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" 
                    class="bg-[#0F3A52] text-white font-black uppercase text-xl border-4 border-black py-4 hover:bg-black transition-colors cursor-pointer shadow-[4px_4px_0_0_#000]">
                Enviar Mensaje
            </button>
        </form>
    </div>

</div>
@endsection