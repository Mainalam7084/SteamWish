@extends('layouts.app')

@section('title', 'Perfil y Dashboard - SteamWish')

@push('scripts')
    <!-- Gráfico -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Previsualización del tema */
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
    
    {{-- Perfil --}}
    <div class="bg-white border-4 border-black nb-shadow p-6 flex flex-col md:flex-row items-center gap-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 theme-bg rounded-bl-full -z-0 opacity-20 transition-colors duration-300" id="profile-blob"></div>
        
        <img src="{{ $user->avatar }}" alt="{{ $user->username }}" class="w-24 h-24 rounded-full border-4 border-black z-10 bg-[#0F3A52]">
        
        <div class="flex-1 z-10 text-center md:text-left">
            <h1 class="text-4xl font-black uppercase text-[#0F3A52] tracking-widest">{{ $user->username }}</h1>
            <p class="text-gray-500 font-bold font-mono text-sm mt-1">ID de Steam: {{ $user->steam_id }}</p>
        </div>
        
        <a href="{{ $user->profile_url }}" target="_blank" class="z-10 bg-white border-4 border-black px-6 py-3 font-black uppercase tracking-widest text-[#0F3A52] shadow-[4px_4px_0_0_#000] hover:-translate-y-1 hover:shadow-[6px_6px_0_0_#000] transition-all flex items-center gap-2">
            <i data-lucide="external-link" class="w-5 h-5"></i>
            Perfil de Steam
        </a>
    </div>

    {{-- Cuadrícula --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Estadísticas --}}
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



        {{-- Gráfico --}}
        <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0_0_#0F3A52] relative">
            <h3 class="text-[#0F3A52] font-black uppercase tracking-widest text-xl mb-4">Actividad SteamWish</h3>
            <div class="relative w-full h-48 flex justify-center">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Tema --}}
        <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0_0_#000] relative flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[#0F3A52] font-black uppercase tracking-widest text-xl flex items-center gap-2">
                        <i data-lucide="palette" class="w-6 h-6 theme-text transition-colors duration-300"></i> Personalizar
                    </h3>
                    <span id="theme-save-msg" class="text-[#1D9E75] font-black text-[10px] uppercase opacity-0 -translate-y-2 transition-all duration-300 flex items-center gap-1 bg-[#1D9E75]/10 border-2 border-[#1D9E75] px-2 py-1 shadow-[2px_2px_0_0_#1D9E75]">
                        <i data-lucide="check-circle" class="w-3 h-3"></i> Guardado
                    </span>
                </div>
                <p class="text-xs font-bold text-gray-500 mb-6 uppercase">Elige el color de acento. Se aplica al instante en todo SteamWish.</p>
                
                @php
                    $currentTheme = Auth::user()->preferences['themeColor'] ?? '#FACC15';
                    $themeColors = [
                        '#FACC15', // Amarillo
                        '#4ADE80', // Verde
                        '#F87171', // Rojo
                        '#60A5FA', // Azul
                        '#C084FC', // Morado
                        '#F472B6'  // Rosa
                    ];
                    $complementaryMap = [
                        '#FACC15' => '#16A34A',
                        '#4ADE80' => '#F472B6',
                        '#F87171' => '#60A5FA',
                        '#60A5FA' => '#F87171',
                        '#C084FC' => '#FACC15',
                        '#F472B6' => '#4ADE80',
                    ];
                @endphp

                <div class="flex flex-wrap gap-4" id="theme-buttons">
                    @foreach($themeColors as $color)
                        <button data-color="{{ $color }}" 
                                class="relative w-10 h-10 border-4 border-black transition-all theme-btn flex items-center justify-center outline-none
                                       {{ $currentTheme === $color ? 'translate-y-1 shadow-none scale-95' : 'hover:scale-110 shadow-[2px_2px_0_0_#000]' }}"
                                style="background-color: {{ $color }};"
                                title="Seleccionar color">
                            @if($currentTheme === $color)
                                <i data-lucide="check" class="w-6 h-6 text-black drop-shadow-md"></i>
                            @endif
                        </button>
                    @endforeach
                </div>
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
        window.activityChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Juegos Guardados', 'Alertas'],
                datasets: [{
                    data: [{{ $wishlistCount }}, {{ $alertsCount }}],
                    backgroundColor: [
                        '{{ Auth::user()->preferences['themeColor'] ?? '#FACC15' }}',
                        '{{ $complementaryMap[Auth::user()->preferences['themeColor'] ?? '#FACC15'] ?? '#16A34A' }}'
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
    let activeColor = '{{ Auth::user()->preferences['themeColor'] ?? '#FACC15' }}';

    const complementaryMap = {
        '#FACC15': '#16A34A',
        '#4ADE80': '#F472B6',
        '#F87171': '#60A5FA',
        '#60A5FA': '#F87171',
        '#C084FC': '#FACC15',
        '#F472B6': '#4ADE80'
    };

    themeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const newColor = btn.dataset.color;
            if (newColor === activeColor) return;
            
            activeColor = newColor;
            const newSecondary = complementaryMap[newColor] || '#16A34A';
            
            // Actualizar botones visualmente
            themeButtons.forEach(b => {
                if (b.dataset.color === newColor) {
                    b.classList.add('translate-y-1', 'shadow-none', 'scale-95');
                    b.classList.remove('hover:scale-110', 'shadow-[2px_2px_0_0_#000]');
                    b.innerHTML = '<i data-lucide="check" class="w-6 h-6 text-black drop-shadow-md"></i>';
                } else {
                    b.classList.remove('translate-y-1', 'shadow-none', 'scale-95');
                    b.classList.add('hover:scale-110', 'shadow-[2px_2px_0_0_#000]');
                    b.innerHTML = '';
                }
            });
            lucide.createIcons();
            
            // Actualizar vista previa instantaneamente (CSS Variables)
            document.documentElement.style.setProperty('--dash-theme', newColor);
            document.documentElement.style.setProperty('--theme-color', newColor);
            document.documentElement.style.setProperty('--theme-secondary', newSecondary);

            // Actualizar color de la gráfica de ChartJS
            if (window.activityChartInstance) {
                window.activityChartInstance.data.datasets[0].backgroundColor[0] = newColor;
                window.activityChartInstance.data.datasets[0].backgroundColor[1] = newSecondary;
                window.activityChartInstance.update();
            }

            // Mostrar mensaje "Guardado"
            const saveMsg = document.getElementById('theme-save-msg');
            if (saveMsg) {
                saveMsg.classList.remove('opacity-0', '-translate-y-2');
                setTimeout(() => {
                    saveMsg.classList.add('opacity-0', '-translate-y-2');
                }, 2000);
            }

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
