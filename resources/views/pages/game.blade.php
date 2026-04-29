@extends('layouts.app')

@section('title', $app_name . ' - SteamWish')
{{-- Esto va aqui? --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="../" class="inline-flex items-center gap-2 px-6 py-3 bg-[#FACC15] text-[#0F3A52] font-black uppercase text-sm border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[2px_2px_0_0_#0F3A52] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i> Volver
            </a>
        </div>

        <article class="bg-white border-4 border-black shadow-[8px_8px_0_0_#0F3A52] p-6 md:p-10">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <h1 class="text-4xl md:text-6xl font-black uppercase text-[#0F3A52]">{{ $app_name }}</h1>

                {{-- Wishlist Button --}}
                <button
                    id="game-detail-wishlist-btn"
                    data-appid="{{ $appid }}"
                    class="wishlist-btn flex items-center gap-3 px-6 py-3 border-4 border-black font-black uppercase text-sm
                           shadow-[4px_4px_0_0_#000] hover:shadow-[2px_2px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px]
                           transition-all {{ $inWishlist ? 'bg-[#FACC15] text-black' : 'bg-white text-[#0F3A52] hover:bg-[#5DA9D6] hover:text-white' }}"
                    aria-label="Toggle wishlist">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                    <span>{{ $inWishlist ? 'Saved' : 'Add to Wishlist' }}</span>
                </button>
            </div>

            <img src="{{ $app_header_img }}" alt="{{ $app_name }}" class="w-full h-auto border-4 border-black shadow-[4px_4px_0_0_#0F3A52] object-cover mb-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-[#5DA9D6] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] p-4 text-[#0F3A52]">
                    <h3 class="font-black uppercase text-sm mb-2">Price</h3>
                    <span class="text-xl font-bold bg-white px-3 py-1 inline-block border-2 border-black">{{ $app_price }} <span class="text-red-600">{{ $discount_formatted }}</span></span>
                </div>
                <div class="bg-[#FACC15] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] p-4 text-[#0F3A52]">
                    <h3 class="font-black uppercase text-sm mb-2">Publisher</h3>
                    <span class="text-xl font-bold bg-white px-3 py-1 inline-block border-2 border-black truncate w-full" title="{{ $app_publisher }}">{{ $app_publisher }}</span>
                </div>
                <div class="bg-[#F5F5F5] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] p-4 text-[#0F3A52]">
                    <h3 class="font-black uppercase text-sm mb-2">Developer</h3>
                    <span class="text-xl font-bold bg-white px-3 py-1 inline-block border-2 border-black truncate w-full" title="{{ $app_developer }}">{{ $app_developer }}</span>
                </div>
            </div>

            @if($app_price_numeric > 0)
                <div class="mb-10">
                <h2 class="text-3xl font-black uppercase text-[#0F3A52] mb-6">Price History</h2>
                <div class="border-4 border-black shadow-[4px_4px_0_0_#0F3A52] p-4 bg-white">
                    <canvas id="priceHistory" style="width:100%;"></canvas> 
                </div>
            </div>


            {{-- TODO: Poner divs o lo q sea --}}
            <p>
                Precio más bajo: {{ $lowest_price }}€
            </p>
            @endif

            <div class="text-lg md:text-xl font-bold border-l-8 border-[#FACC15] pl-6 py-4 mb-10 text-[#0F3A52] bg-[#F5F5F5] pr-4 shadow-[2px_2px_0_0_#0F3A52] border-y-2 border-r-2 border-black border-l-black">
                {{ $app_short_desc }}
            </div>

            @if(!empty($screenshots))
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-black uppercase text-[#0F3A52]">Screenshots</h2>
                    <div class="flex gap-4">
                        <button id="btn-prev-screenshot" class="p-2 bg-[#FACC15] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[2px_2px_0_0_#0F3A52] hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[#0F3A52]" aria-label="Previous screenshot">
                            <i data-lucide="chevron-left" class="w-6 h-6"></i>
                        </button>
                        <button id="btn-next-screenshot" class="p-2 bg-[#FACC15] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[2px_2px_0_0_#0F3A52] hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-[#0F3A52]" aria-label="Next screenshot">
                            <i data-lucide="chevron-right" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
                <div id="screenshot-container" class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory rounded-sm !scrollbar-thin !scrollbar-thumb-[#0F3A52] !scrollbar-track-[#F5F5F5] scroll-smooth">
                    @foreach($screenshots as $index => $screenshot)
                        <div class="snap-center shrink-0 w-[85%] md:w-[60%] border-4 border-black shadow-[4px_4px_0_0_#0F3A52] bg-white flex flex-col">
                            <img src="{{ $screenshot['path_thumbnail'] }}" class="w-full h-auto object-cover border-b-4 border-black" alt="Screenshot {{ $index + 1 }}">
                            <div class="bg-[#FACC15] p-3 text-center font-black text-[#0F3A52] border-t-2 border-black">
                                {{ $index + 1 }} / {{ count($screenshots) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('screenshot-container');
                        const btnPrev = document.getElementById('btn-prev-screenshot');
                        const btnNext = document.getElementById('btn-next-screenshot');

                        if(container && btnPrev && btnNext) {
                            const scrollAmount = () => {
                                const firstCard = container.querySelector('div.shrink-0');
                                return firstCard ? firstCard.offsetWidth + 24 : 300; // 24px is to match gap-6
                            };

                            btnPrev.addEventListener('click', () => {
                                container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
                            });

                            btnNext.addEventListener('click', () => {
                                container.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
                            });
                        }
                    });
                </script>
            @endif

                @if ($app_price_numeric > 0)
                    {{-- HISTORIAL DE PRECIOS --}}
                <script>
                    Chart.defaults.global.defaultFontFamily = "'Inter', 'Roboto', 'sans-serif'";
                    Chart.defaults.global.defaultFontColor = "#0F3A52";
                    
                    new Chart("priceHistory", {
                        type: "line",
                        data: {
                            labels: {!! json_encode($price_history_timestamps) !!},
                            datasets: [{
                                fill: true,
                                steppedLine: true,
                                lineTension: 0,
                                backgroundColor: "rgba(250, 204, 21, 0.2)",
                                borderColor: "#0F3A52",
                                borderWidth: 4,
                                pointBackgroundColor: "#FACC15",
                                pointBorderColor: "#0F3A52",
                                pointBorderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                data: {!! json_encode($price_history_prices) !!}
                            }]
                        },
                        options: {
                            legend: { display: false },
                            layout: {
                                padding: { top: 10, right: 10, bottom: 10, left: 10 }
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        color: "rgba(0,0,0,0)",
                                        zeroLineColor: "#0F3A52",
                                        zeroLineWidth: 4
                                    },
                                    ticks: { fontStyle: "bold" }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        color: "rgba(15, 58, 82, 0.1)",
                                        zeroLineColor: "#0F3A52",
                                        zeroLineWidth: 4
                                    },
                                    ticks: {
                                        fontStyle: "bold",
                                        callback: function(value) {
                                            return value.toLocaleString('es-ES', {
                                                style: 'currency',
                                                currency: 'EUR'
                                            });
                                        }
                                    }
                                }]
                            },
                            tooltips: {
                                backgroundColor: "#0F3A52",
                                titleFontColor: "#FACC15",
                                bodyFontColor: "#fff",
                                bodyFontStyle: "bold",
                                borderColor: "#000",
                                borderWidth: 3,
                                cornerRadius: 0,
                                displayColors: false,
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        let value = tooltipItem.yLabel;
                                        return value.toLocaleString('es-ES', {
                                            style: 'currency',
                                            currency: 'EUR'
                                        });
                                    }
                                }
                            }
                        }
                    });
                </script>
                @endif
                
                @endpush
            <div class="mt-10">
                <h2 class="text-3xl font-black uppercase text-[#0F3A52] mb-6">Detailed Description</h2>
                <div class="game-description border-4 border-black shadow-[4px_4px_0_0_#0F3A52] p-6 bg-[#F5F5F5] text-[#0F3A52] text-md md:text-lg font-medium space-y-4">
                    <style>
                        .game-description h2 { font-size: 1.5rem; font-weight: 900; margin-top: 1.5rem; margin-bottom: 0.5rem; text-transform: uppercase; border-bottom: 4px solid #0F3A52; display: inline-block; padding-bottom: 2px;}
                        .game-description img { max-width: 100%; height: auto; border: 4px solid black; box-shadow: 4px 4px 0 0 #0F3A52; margin: 1rem 0; }
                        .game-description ul { list-style-type: square; padding-left: 2rem; margin: 1rem 0; }
                        .game-description li { margin-bottom: 0.5rem; }
                        .game-description a { color: #5DA9D6; text-decoration: underline; font-weight: bold; background: #0F3A52; color: #FACC15; padding: 2px 6px; border: 2px solid black;}
                        .game-description p { margin-bottom: 1rem; line-height: 1.6; }
                    </style>
                    {!! $app_detailed_desc !!}
                </div>
            </div>
        </article>
    </div>
@endsection
