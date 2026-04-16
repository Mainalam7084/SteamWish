@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- HERO -->
        <div class="my-12 w-full">
            <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#0F3A52] md:shadow-[24px_24px_0_0_#0F3A52] flex flex-col md:flex-row w-full overflow-hidden hover:-translate-y-2 hover:-translate-x-2 hover:shadow-[24px_24px_0_0_#0F3A52] md:hover:shadow-[32px_32px_0_0_#0F3A52] transition-all duration-300">
                
                <!-- Visual Side -->
                <div class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-r-8 border-black relative bg-[#FACC15] flex items-center justify-center p-16 min-h-[350px] overflow-hidden">
                    <!-- Background decor graphic -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none">
                        <span class="text-[350px] font-black text-black leading-none transform rotate-12 select-none">!</span>
                    </div>
                    <div class="relative z-10 text-center transform -rotate-3">
                        <h2 class="text-7xl md:text-[6rem] font-black uppercase text-black drop-shadow-[6px_6px_0_#fff] leading-none">
                            EQUIPO
                        </h2>
                        <div class="inline-block bg-black text-[#FACC15] font-black uppercase px-6 py-2 border-4 border-white mt-4 text-2xl shadow-[6px_6px_0_0_#fff]">
                            STEAMWISH
                        </div>
                    </div>
                </div>
                
                <!-- Content Side -->
                <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#0F3A52]">
                    <h1 class="text-5xl md:text-7xl font-black uppercase text-[#FACC15] mb-10 leading-tight tracking-tighter relative z-10 drop-shadow-[4px_4px_0_#000]">
                        Sobre Nosotros
                    </h1>
                    
                    <div class="text-xl md:text-3xl font-bold space-y-8 relative z-10 w-full">
                        <p class="inline-block bg-white text-[#0F3A52] p-4 md:p-6 border-4 border-black shadow-[8px_8px_0_0_#FACC15] transform rotate-1">
                            En 2026, un grupo apasionado de desarrolladores se unió para crear la mejor plataforma.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- DAVID -->
        <div class="my-24 w-full">
            <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#1D9E75] md:shadow-[24px_24px_0_0_#1D9E75] flex flex-col md:flex-row-reverse w-full overflow-hidden hover:translate-y-2 hover:-translate-x-2 hover:shadow-[8px_8px_0_0_#1D9E75] md:hover:shadow-[12px_12px_0_0_#1D9E75] transition-all duration-300">
                
                <!-- Image Side -->
                <div class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-l-8 border-black relative bg-[#1D9E75] group flex-shrink-0">
                    <div class="aspect-square md:aspect-auto md:h-full w-full relative overflow-hidden">
                        <div class="imagen-grid-animada absolute inset-0 w-full h-full object-cover mix-blend-luminosity group-hover:mix-blend-normal transition-all duration-500 scale-105 group-hover:scale-100" data-imagen="{{ asset('img/david2.png') }}"></div>
                    </div>
                    
                    <!-- Decorative Badge -->
                    <div class="absolute bottom-6 right-6 bg-black text-[#1D9E75] font-black uppercase px-4 py-2 border-4 border-black transform rotate-3 shadow-[6px_6px_0_0_#fff] text-xl md:text-2xl z-10 pointer-events-none">
                        >_ ROOT
                    </div>
                </div>
                
                <!-- Content Side -->
                <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#0F3A52]">
                    <!-- Background decor graphic -->
                    <div class="absolute top-10 left-10 text-[#1D9E75] opacity-20 text-[180px] font-black pointer-events-none leading-none select-none font-mono">
                        {}
                    </div>
                    
                    <h2 class="text-6xl md:text-[5.5rem] font-black uppercase text-[#1D9E75] mb-0 leading-[0.9] tracking-tighter relative z-10 drop-shadow-[4px_4px_0_#000]">
                        DAVID ROLLON
                    </h2>
                    
                    <div class="inline-flex self-start bg-[#FACC15] text-black font-black uppercase px-6 py-2 border-4 border-black mt-6 mb-10 shadow-[6px_6px_0_0_#1D9E75] text-xl md:text-2xl transform -rotate-1 z-10">
                        Backend Dev
                    </div>
                    
                    <div class="text-2xl md:text-3xl font-bold text-black relative z-10 space-y-4">
                        <p class="inline-block bg-[#1D9E75] text-white p-4 border-4 border-black shadow-[6px_6px_0_0_#000]">
                            "El backend es oscuro y alberga terrores."
                        </p>
                        <p class="inline-block bg-white px-2 py-1 border-2 border-black text-xl md:text-2xl mt-4">
                            (Pero mis APIs rara vez explotan).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN-->
        <div class="my-24 w-full">
            <div
                class="bg-white border-8 border-black shadow-[16px_16px_0_0_#0F3A52] md:shadow-[24px_24px_0_0_#0F3A52] flex flex-col md:flex-row w-full overflow-hidden hover:translate-y-2 hover:translate-x-2 hover:shadow-[8px_8px_0_0_#0F3A52] md:hover:shadow-[12px_12px_0_0_#0F3A52] transition-all duration-300">

                <!-- Image Side -->
                <div
                    class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-r-8 border-black relative bg-[#EF9F27] group flex-shrink-0">
                    <div class="aspect-square md:aspect-auto md:h-full w-full relative overflow-hidden">
                        <!-- We add grayscale on default and full color on hover for extra edge -->
                        <div class="imagen-grid-animada absolute inset-0 w-full h-full object-cover mix-blend-luminosity group-hover:mix-blend-normal transition-all duration-500 scale-105 group-hover:scale-100"
                            data-imagen="{{ asset('img/main.png') }}"></div>
                    </div>

                    <!-- Chaotic Yellow Badge -->
                    <div
                        class="absolute top-6 left-6 bg-[#FACC15] text-black font-black uppercase px-4 py-2 border-4 border-black transform -rotate-6 shadow-[6px_6px_0_0_#0F3A52] text-2xl md:text-3xl z-10 pointer-events-none">
                        TOP DEV
                    </div>
                </div>

                <!-- Content Side -->
                <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#F5F5F5]">
                    <!-- Background decor graphic -->
                    <div
                        class="absolute -top-10 -right-10 text-[#EF9F27] opacity-20 text-[200px] font-black pointer-events-none leading-none select-none">
                        *
                    </div>

                    <h2
                        class="text-6xl md:text-[5.5rem] font-black uppercase text-[#0F3A52] mb-0 leading-[0.9] tracking-tighter relative z-10 drop-shadow-[4px_4px_0_#EF9F27]">
                        MAIN ALAM
                    </h2>

                    <div
                        class="inline-flex self-start bg-black text-[#EF9F27] font-black uppercase px-6 py-2 border-4 border-black mt-6 mb-10 shadow-[6px_6px_0_0_#000] text-xl md:text-2xl transform rotate-2 z-10">
                        Frontend Dev
                    </div>

                    <div class="text-2xl md:text-3xl font-bold text-[#0F3A52] relative z-10 space-y-4">
                        <p
                            class="inline-block bg-[#0F3A52] text-white p-4 border-4 border-black shadow-[6px_6px_0_0_#EF9F27]">
                            "Centro un DIV a la primera y sin Google."
                        </p>
                        <p class="inline-block bg-white px-2 py-1 border-2 border-black text-xl md:text-2xl mt-4">
                            (Es mentira, vivo con IA y rezo para que funcione todo).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- GEO -->
        <div class="my-24 w-full">
            <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#7F77DD] md:shadow-[24px_24px_0_0_#7F77DD] flex flex-col md:flex-row w-full overflow-hidden hover:translate-y-2 hover:translate-x-2 hover:shadow-[8px_8px_0_0_#7F77DD] md:hover:shadow-[12px_12px_0_0_#7F77DD] transition-all duration-300">
                
                <!-- Image Side -->
                <div class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-r-8 border-black relative bg-[#7F77DD] group flex-shrink-0">
                    <div class="aspect-square md:aspect-auto md:h-full w-full relative overflow-hidden">
                        <!-- Custom mix blend for design -->
                        <div class="imagen-grid-animada absolute inset-0 w-full h-full object-cover mix-blend-hard-light grayscale group-hover:mix-blend-normal group-hover:grayscale-0 transition-all duration-500 scale-105 group-hover:scale-100" data-imagen="{{ asset('img/geovanny.jpg') }}"></div>
                    </div>
                    
                    <!-- Decorative Badge -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#FACC15] text-[#7F77DD] font-black uppercase px-6 py-4 border-8 border-black -rotate-12 shadow-[8px_8px_0_0_#000] text-4xl md:text-5xl z-10 pointer-events-none mix-blend-exclusion">
                        UI/UX
                    </div>
                </div>
                
                <!-- Content Side -->
                <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#FACC15]">
                    <!-- Background decor graphic -->
                    <div class="absolute -bottom-10 right-0 text-[#7F77DD] opacity-30 text-[250px] font-black pointer-events-none leading-none select-none">
                        @
                    </div>
                    
                    <h2 class="text-5xl md:text-6xl font-black uppercase text-black mb-0 leading-[0.9] tracking-tighter relative z-10 drop-shadow-[2px_2px_0_#fff]">
                               GEOVANNY JIMENEZ
                          </h2>
                    
                    <div class="inline-flex self-start bg-[#7F77DD] text-white font-black uppercase px-6 py-2 border-4 border-black mt-6 mb-10 shadow-[6px_6px_0_0_#000] text-xl md:text-2xl transform rotate-1 z-10 rounded-full">
                        Designer
                    </div>
                    
                    <div class="text-2xl md:text-3xl font-bold text-black relative z-10 space-y-4">
                        <p class="inline-block bg-[#000] text-[#FACC15] p-4 border-4 border-white shadow-[6px_6px_0_0_#7F77DD]">
                            "Muevo botones 1px a la derecha... y 1px a la izquierda."
                        </p>
                        <p class="inline-block bg-white px-2 py-1 border-2 border-black text-xl md:text-2xl mt-4">
                            (Pura magia visual para que no te sangren los ojos).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORIA -->
        <div class="my-24 w-full">
            <div class="bg-white border-8 border-black shadow-[16px_16px_0_0_#FACC15] md:shadow-[24px_24px_0_0_#FACC15] flex flex-col md:flex-row-reverse w-full overflow-hidden hover:-translate-y-2 hover:-translate-x-2 hover:shadow-[24px_24px_0_0_#FACC15] md:hover:shadow-[32px_32px_0_0_#FACC15] transition-all duration-300">
                
                <!-- Visual Side -->
                <div class="w-full md:w-5/12 border-b-8 md:border-b-0 md:border-l-8 border-black relative bg-[#1D9E75] flex items-center justify-center p-16 min-h-[350px] overflow-hidden">
                    <!-- Background decor graphic -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none">
                        <span class="text-[350px] font-black text-black leading-none transform -rotate-12 select-none">H</span>
                    </div>
                    <div class="relative z-10 text-center transform rotate-3">
                        <h2 class="text-5xl md:text-[6rem] font-black uppercase text-white drop-shadow-[6px_6px_0_#000] leading-none">
                            2026
                        </h2>
                        <div class="inline-block bg-black text-[#FACC15] font-black uppercase px-6 py-2 border-4 border-white mt-4 text-2xl shadow-[6px_6px_0_0_#fff]">
                            ORIGEN
                        </div>
                    </div>
                </div>
                
                <!-- Content Side -->
                <div class="w-full md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative bg-[#0F3A52]">
                    <h2 class="text-5xl md:text-6xl font-black uppercase text-[#FACC15] mb-10 leading-tight tracking-tighter relative z-10 drop-shadow-[2px_2px_0_#000]">
                               Nuestra Historia
                         </h2>
                    
                    <div class="text-xl md:text-3xl font-bold space-y-8 relative z-10 w-full">
                        <p class="inline-block bg-white text-black p-4 md:p-6 border-4 border-black shadow-[8px_8px_0_0_#1D9E75] transform -rotate-1">
                            "Comenzamos como un proyecto de fin de grado."
                        </p>
                        <div class="mt-8">
                            <p class="inline-block bg-[#FACC15] text-black px-4 py-3 md:px-6 md:py-4 border-4 border-black shadow-[8px_8px_0_0_#1D9E75] transform rotate-1">
                                Hoy somos una plataforma <span class="bg-black text-white px-3 py-1">indestructible</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
