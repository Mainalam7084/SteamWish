@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-16 text-black font-sans">
    
    <h1 class="text-4xl md:text-5xl font-black uppercase text-[#0F3A52] mb-16 border-b-4 border-black pb-4">
        Sobre Nosotros
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
        
        <div class="flex flex-col gap-12">
            
            <div class="space-y-6">
                <p class="text-sm font-black uppercase tracking-widest text-[#0F3A52]">EL EQUIPO / LOS CREADORES</p>
                <p class="text-xl leading-relaxed font-bold border-l-4 border-black pl-6">
                    En el año 2026, un grupo apasionado de desarrolladores se unió para dar vida a SteamWish. Nuestra misión es simple: crear la mejor plataforma para gestionar y descubrir tus deseos de juego.
                </p>
                <p class="text-lg leading-relaxed text-gray-700">
                    Nacimos de la frustración de perder el rastro de esos juegos increíbles que queríamos jugar algún día. Combinando nuestra pasión por el gaming y el desarrollo web, diseñamos una herramienta brutalista, directa y fácil de usar para gamers de todo el mundo.
                </p>
            </div>

           <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                
                <div class="border-4 border-black shadow-[8px_8px_0_0_#0F3A52] aspect-[3/4]">
                    <div class="imagen-grid-animada w-full h-full" data-imagen="{{ asset('img/animacion.png') }}"></div>
                </div>
                
                <div class="border-4 border-black shadow-[8px_8px_0_0_#0F3A52] aspect-[3/4]">
                    <div class="imagen-grid-animada w-full h-full" data-imagen="{{ asset('img/animacion.png') }}"></div>
                </div>
                
                <div class="border-4 border-black shadow-[8px_8px_0_0_#0F3A52] aspect-[3/4]">
                    <div class="imagen-grid-animada w-full h-full" data-imagen="{{ asset('img/animacion.png') }}"></div>
                </div>

            </div>

        </div>

        <div class="flex flex-col gap-12 text-right md:text-left">
            
            <h2 class="text-6xl md:text-8xl font-black uppercase tracking-tighter text-[#0F3A52] leading-none">
                PASIÓN POR EL DESARROLLO
            </h2>

        </div>

    </div>

    <div class="mt-24 border-t-4 border-black pt-12">
        <h3 class="text-3xl font-black uppercase text-[#0F3A52] mb-8">Nuestra Historia</h3>
        <p class="max-w-4xl text-lg text-gray-700 leading-relaxed">
            Comenzamos como un proyecto de fin de grado y evolucionamos a algo más grande. SteamWish es el resultado de cientos de horas de diseño, codificación y, por supuesto, de jugar muchos juegos para probar nuestra propia plataforma. Creemos en la comunidad y en hacer herramientas directas al grano, sin adornos innecesarios.
        </p>
    </div>

</div>
@endsection