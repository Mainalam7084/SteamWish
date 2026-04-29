@extends('layouts.app')

@section('title', 'Perfil y Dashboard - SteamWish')

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Dashboard Theme Preview */
        :root {
            --dash-theme: {{ $themeColor ?? '#FACC15' }};
        }
        .theme-bg { background-color: var(--dash-theme) !important; }
        .theme-text { color: var(--dash-theme) !important; }
        .theme-shadow { box-shadow: 4px 4px 0 0 var(--dash-theme) !important; }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Header Profile --}}
    <div class="bg-white border-4 border-black nb-shadow p-6 flex flex-col md:flex-row items-center gap-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 theme-bg rounded-bl-full -z-0 opacity-20 transition-colors duration-300" id="profile-blob"></div>
        
        <img src="{{ $user->avatar }}" alt="{{ $user->username }}" class="w-24 h-24 rounded-full border-4 border-black z-10 bg-[#0F3A52]">
        
        <div class="flex-1 z-10 text-center md:text-left">
            <h1 class="text-4xl font-black uppercase text-[#0F3A52] tracking-widest">{{ $user->username }}</h1>
            <p class="text-gray-500 font-bold font-mono text-sm mt-1">Steam ID: {{ $user->steam_id }}</p>
        </div>
        
        <a href="{{ $user->profile_url }}" target="_blank" class="z-10 bg-white border-4 border-black px-6 py-3 font-black uppercase tracking-widest text-[#0F3A52] shadow-[4px_4px_0_0_#000] hover:-translate-y-1 hover:shadow-[6px_6px_0_0_#000] transition-all flex items-center gap-2">
            <i data-lucide="external-link" class="w-5 h-5"></i>
            Perfil de Steam
        </a>
    </div>

    {{-- Static Dashboard Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Stats --}}
        <div class="bg-[#0F3A52] border-4 border-black p-6 flex flex-col justify-between shadow-[8px_8px_0_0_#000] hover:-translate-y-1 hover:shadow-[10px_10px_0_0_#000] transition-all relative">
            <div>
                <h3 class="text-white font-black uppercase tracking-widest text-xl mb-6 flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-6 h-6 theme-text transition-colors duration-300"></i> Estadísticas
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b-2 border-white/20 pb-2">
                        <span class="text-gray-300 font-bold text-sm uppercase">Juegos en Wishlist</span>
                        <span class="text-white font-black text-2xl">{{ $wishlistCount }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-300 font-bold text-sm uppercase">Alertas Activas</span>
                        <span class="theme-text font-black text-2xl transition-colors duration-300">{{ $alertsCount }}</span>
                    </div>
                </div>
            </div>
        </div>



        {{-- Chart.js --}}
        <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0_0_#0F3A52] relative">
            <h3 class="text-[#0F3A52] font-black uppercase tracking-widest text-xl mb-4">Actividad SteamWish</h3>
            <div class="relative w-full h-48 flex justify-center">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Theme Configurator --}}
        <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0_0_#000] relative">
            <h3 class="text-[#0F3A52] font-black uppercase tracking-widest text-xl mb-6 flex items-center gap-2">
                <i data-lucide="palette" class="w-6 h-6 theme-text transition-colors duration-300"></i> Personalizar Web
            </h3>
            <p class="text-xs font-bold text-gray-500 mb-4 uppercase">Elige el color principal de acento para la web.</p>
            
            <div class="flex flex-wrap gap-4" id="theme-buttons">
                <button data-color="#FACC15" class="w-10 h-10 bg-[#FACC15] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
                <button data-color="#4ADE80" class="w-10 h-10 bg-[#4ADE80] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
                <button data-color="#F87171" class="w-10 h-10 bg-[#F87171] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
                <button data-color="#60A5FA" class="w-10 h-10 bg-[#60A5FA] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
                <button data-color="#C084FC" class="w-10 h-10 bg-[#C084FC] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
                <button data-color="#F472B6" class="w-10 h-10 bg-[#F472B6] border-4 border-black hover:scale-110 transition-transform theme-btn shadow-[2px_2px_0_0_#000]"></button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = '{{ csrf_token() }}';

    // 1. Iniciar Chart.js
    const ctx = document.getElementById('activityChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Juegos Guardados', 'Alertas'],
                datasets: [{
                    data: [{{ max(1, $wishlistCount) }}, {{ max(1, $alertsCount) }}],
                    backgroundColor: [
                        '#FACC15',
                        '#16A34A'
                    ],
                    borderWidth: 4,
                    borderColor: '#000',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace', weight: 'bold', size: 10 }, color: '#0F3A52' }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // 3. Theme Configurator
    const themeButtons = document.querySelectorAll('.theme-btn');
    themeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const newColor = btn.dataset.color;
            
            // Actualizar vista previa instantaneamente (CSS Variables)
            document.documentElement.style.setProperty('--dash-theme', newColor);
            
            // Actualizar el CSS global de Tailwind para que parezca que cambia toda la web
            document.documentElement.style.setProperty('--theme-color', newColor);

            // Enviar a la BD
            fetch("{{ route('user.preferences') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ themeColor: newColor })
            });
        });
    });

});
</script>
@endpush
@endsection
