@extends('layouts.app')

@section('title', 'SteamWish – Dashboard')

@push('scripts')
    <style>
        /* ── Loading Screen ─────────────────────────────── */
        #sw-loader-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        #sw-loader-overlay.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .sw-loader-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 900;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            color: #0F3A52;
            text-align: center;
            line-height: 1;
        }

        .sw-loader-title span {
            color: #FACC15;
            -webkit-text-stroke: 2px #000;
        }

        .sw-loader-badge {
            background: #FACC15;
            border: 3px solid #000;
            color: #000;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 900;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 0.25rem 0.75rem;
            box-shadow: 3px 3px 0 0 #000;
            display: none;
        }

        /* Progress bar */
        .sw-progress-track {
            width: 200px;
            height: 12px;
            background: #fff;
            border: 3px solid #000;
            box-shadow: 3px 3px 0 0 #000;
            overflow: hidden;
        }

        .sw-progress-fill {
            height: 100%;
            width: 0%;
            background: #FACC15;
            border-right: 2px solid #000;
            transition: width 3s linear;
        }

        /* ── Loader character (original styles preserved + tinted) ── */
        .loader {
            scale: 0.75;
            position: relative;
            width: 200px;
            height: 200px;
            translate: 10px -20px;
        }

        .loader svg {
            position: absolute;
            top: 0;
            left: 0;
        }

        .head {
            translate: 27px -30px;
            z-index: 3;
            animation: bob 1s infinite ease-in;
        }

        .bod {
            translate: 0px 30px;
            z-index: 3;
            animation: bob 1s infinite ease-in-out;
        }

        .legr {
            translate: 75px 135px;
            z-index: 0;
            animation: rstep 1s infinite ease-in;
            animation-delay: 0.45s;
        }

        .legl {
            translate: 30px 155px;
            z-index: 3;
            animation: lstep 1s infinite ease-in;
        }

        @keyframes bob {
            0% {
                transform: translateY(0) rotate(3deg);
            }

            5% {
                transform: translateY(0) rotate(3deg);
            }

            25% {
                transform: translateY(5px) rotate(0deg);
            }

            50% {
                transform: translateY(0px) rotate(-3deg);
            }

            70% {
                transform: translateY(5px) rotate(0deg);
            }

            100% {
                transform: translateY(0) rotate(3deg);
            }
        }

        @keyframes lstep {
            0% {
                transform: translateY(0) rotate(-5deg);
            }

            33% {
                transform: translateY(-15px) translate(32px) rotate(35deg);
            }

            66% {
                transform: translateY(0) translate(25px) rotate(-25deg);
            }

            100% {
                transform: translateY(0) rotate(-5deg);
            }
        }

        @keyframes rstep {
            0% {
                transform: translateY(0) translate(0px) rotate(-5deg);
            }

            33% {
                transform: translateY(-10px) translate(30px) rotate(35deg);
            }

            66% {
                transform: translateY(0) translate(20px) rotate(-25deg);
            }

            100% {
                transform: translateY(0) translate(0px) rotate(-5deg);
            }
        }

        #gnd {
            translate: -140px 0;
            rotate: 10deg;
            z-index: -1;
            filter: blur(0.5px) drop-shadow(1px 3px 5px #000000);
            opacity: 0.25;
            animation: scroll 5s infinite linear;
        }

        @keyframes scroll {
            0% {
                transform: translateY(25px) translate(50px);
                opacity: 0;
            }

            33% {
                opacity: 0.25;
            }

            66% {
                opacity: 0.25;
            }

            to {
                transform: translateY(-50px) translate(-100px);
                opacity: 0;
            }
        }
    </style>

    {{-- ══════════════════════════════════════ --}}
    {{--  LOADING SCREEN (home only)           --}}
    {{-- ══════════════════════════════════════ --}}
    <div id="sw-loader-overlay" aria-hidden="true">
        {{-- Walking character --}}
            <div class="loader">
                <svg class="legl" version="1.1" xmlns="http://www.w3.org/2000/svg" width="20.69332" height="68.19944"
                    viewBox="0,0,20.69332,68.19944">
                    <g transform="translate(-201.44063,-235.75466)">
                        <g stroke-miterlimit="10">
                            <path d="" fill="#FACC15" stroke="none" stroke-width="0.5"></path>
                            <path d="" fill-opacity="0.26667" fill="#FACC15" stroke-opacity="0.48627" stroke="#ffffff"
                                stroke-width="0"></path>
                            <path
                                d="M218.11971,301.20087c-2.20708,1.73229 -4.41416,0 -4.41416,0l-1.43017,-1.1437c-1.42954,-1.40829 -3.04351,-2.54728 -4.56954,-3.87927c-0.95183,-0.8308 -2.29837,-1.49883 -2.7652,-2.55433c-0.42378,-0.95815 0.14432,-2.02654 0.29355,-3.03399c0.41251,-2.78499 1.82164,-5.43386 2.41472,-8.22683c1.25895,-4.44509 2.73863,-8.98683 3.15318,-13.54796c0.22615,-2.4883 -0.21672,-5.0155 -0.00278,-7.50605c0.30636,-3.56649 1.24602,-7.10406 1.59992,-10.6738c0.29105,-2.93579 -0.00785,-5.9806 -0.00785,-8.93046c0,0 0,-2.44982 3.12129,-2.44982c3.12129,0 3.12129,2.44982 3.12129,2.44982c0,3.06839 0.28868,6.22201 -0.00786,9.27779c-0.34637,3.56935 -1.30115,7.10906 -1.59992,10.6738c-0.2103,2.50918 0.22586,5.05326 -0.00278,7.56284c-0.43159,4.7371 -1.94029,9.46317 -3.24651,14.07835c-0.47439,2.23403 -1.29927,4.31705 -2.05805,6.47156c-0.18628,0.52896 -0.1402,1.0974 -0.327,1.62624c-0.09463,0.26791 -0.64731,0.47816 -0.50641,0.73323c0.19122,0.34617 0.86423,0.3445 1.2346,0.58502c1.88637,1.22503 3.50777,2.79494 5.03,4.28305l0.96971,0.73991c0,0 2.20708,1.73229 0,3.46457z"
                                fill="none" stroke="#111" stroke-width="7"></path>
                        </g>
                    </g>
                </svg>

                <svg class="legr" version="1.1" xmlns="http://www.w3.org/2000/svg" width="41.02537" height="64.85502"
                    viewBox="0,0,41.02537,64.85502">
                    <g transform="translate(-241.54137,-218.44347)">
                        <g stroke-miterlimit="10">
                            <path
                                d="M279.06674,279.42662c-2.27967,1.98991 -6.08116,0.58804 -6.08116,0.58804l-2.47264,-0.92915c-2.58799,-1.18826 -5.31176,-2.08831 -7.99917,-3.18902c-1.67622,-0.68654 -3.82471,-1.16116 -4.93147,-2.13229c-1.00468,-0.88156 -0.69132,-2.00318 -0.92827,-3.00935c-0.65501,-2.78142 0.12275,-5.56236 -0.287,-8.37565c-0.2181,-4.51941 -0.17458,-9.16283 -1.60696,-13.68334c-0.78143,-2.46614 -2.50162,-4.88125 -3.30086,-7.34796c-1.14452,-3.53236 -1.40387,-7.12078 -2.48433,-10.66266c-0.88858,-2.91287 -2.63779,-5.85389 -3.93351,-8.74177c0,0 -1.07608,-2.39835 3.22395,-2.81415c4.30003,-0.41581 2.41605,1.98254 2.41605,1.98254c1.34779,3.00392 3.13072,6.05282 4.06444,9.0839c1.09065,3.54049 1.33011,7.13302 2.48433,10.66266c0.81245,2.48448 2.5308,4.917 3.31813,7.40431c1.48619,4.69506 1.48366,9.52281 1.71137,14.21503c0.32776,2.25028 0.10631,4.39942 0.00736,6.60975c-0.02429,0.54266 0.28888,1.09302 0.26382,1.63563c-0.01269,0.27488 -0.68173,0.55435 -0.37558,0.78529c0.41549,0.31342 1.34191,0.22213 1.95781,0.40826c3.13684,0.94799 6.06014,2.26892 8.81088,3.52298l1.66093,0.59519c0,0 6.76155,1.40187 4.48187,3.39177z"
                                fill="none" stroke="#111" stroke-width="7"></path>
                            <path d="" fill="#FACC15" stroke="none" stroke-width="0.5"></path>
                            <path d="" fill-opacity="0.26667" fill="#FACC15" stroke-opacity="0.48627" stroke="#ffffff"
                                stroke-width="0"></path>
                        </g>
                    </g>
                </svg>

                <div class="bod">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="144.10576" height="144.91623"
                        viewBox="0,0,144.10576,144.91623">
                        <g transform="translate(-164.41679,-112.94712)">
                            <g stroke-miterlimit="10">
                                <path
                                    d="M166.9168,184.02633c0,-36.49454 35.0206,-66.07921 72.05288,-66.07921c37.03228,0 67.05288,29.58467 67.05288,66.07921c0,6.94489 -1.08716,13.63956 -3.10292,19.92772c-2.71464,8.46831 -7.1134,16.19939 -12.809,22.81158c-2.31017,2.68194 -7.54471,12.91599 -7.54471,12.91599c0,0 -5.46714,-1.18309 -8.44434,0.6266c-3.86867,2.35159 -10.95356,10.86714 -10.95356,10.86714c0,0 -6.96906,-3.20396 -9.87477,-2.58085c-2.64748,0.56773 -6.72538,5.77072 -6.72538,5.77072c0,0 -5.5023,-4.25969 -7.5982,-4.25969c-3.08622,0 -9.09924,3.48259 -9.09924,3.48259c0,0 -6.0782,-5.11244 -9.00348,-5.91884c-4.26461,-1.17561 -12.23343,0.75049 -12.23343,0.75049c0,0 -5.18164,-8.26065 -7.60688,-9.90388c-3.50443,-2.37445 -8.8271,-3.95414 -8.8271,-3.95414c0,0 -5.33472,-8.81718 -7.27019,-11.40895c-4.81099,-6.44239 -13.46422,-9.83437 -15.65729,-17.76175c-1.53558,-5.55073 -2.35527,-21.36472 -2.35527,-21.36472z"
                                    fill="#0F3A52" stroke="#0F3A52" stroke-width="5" stroke-linecap="butt"></path>
                                <path
                                    d="M167.94713,180c0,-37.03228 35.0206,-67.05288 72.05288,-67.05288c37.03228,0 67.05288,30.0206 67.05288,67.05288c0,7.04722 -1.08716,13.84053 -3.10292,20.22135c-2.71464,8.59309 -7.1134,16.43809 -12.809,23.14771c-2.31017,2.72146 -7.54471,13.1063 -7.54471,13.1063c0,0 -5.46714,-1.20052 -8.44434,0.63584c-3.86867,2.38624 -10.95356,11.02726 -10.95356,11.02726c0,0 -6.96906,-3.25117 -9.87477,-2.61888c-2.64748,0.5761 -6.72538,5.85575 -6.72538,5.85575c0,0 -5.5023,-4.32246 -7.5982,-4.32246c-3.08622,0 -9.09924,3.5339 -9.09924,3.5339c0,0 -6.0782,-5.18777 -9.00348,-6.00605c-4.26461,-1.19293 -12.23343,0.76155 -12.23343,0.76155c0,0 -5.18164,-8.38236 -7.60688,-10.04981c-3.50443,-2.40943 -8.8271,-4.0124 -8.8271,-4.0124c0,0 -5.33472,-8.9471 -7.27019,-11.57706c-4.81099,-6.53732 -13.46422,-9.97928 -15.65729,-18.02347c-1.53558,-5.63252 -2.35527,-21.67953 -2.35527,-21.67953z"
                                    fill="#0F3A52" stroke="none" stroke-width="0" stroke-linecap="butt"></path>
                                <path d="" fill="#FACC15" stroke="none" stroke-width="0.5" stroke-linecap="butt"></path>
                                <path d="" fill-opacity="0.26667" fill="#FACC15" stroke-opacity="0.48627" stroke="#ffffff"
                                    stroke-width="0" stroke-linecap="butt"></path>
                                <path
                                    d="M216.22445,188.06994c0,0 1.02834,11.73245 -3.62335,21.11235c-4.65169,9.3799 -13.06183,10.03776 -13.06183,10.03776c0,0 7.0703,-3.03121 10.89231,-10.7381c4.34839,-8.76831 5.79288,-20.41201 5.79288,-20.41201z"
                                    fill="none" stroke="#FACC15" stroke-width="3" stroke-linecap="round"></path>
                            </g>
                        </g>
                    </svg>

                    <svg class="head" version="1.1" xmlns="http://www.w3.org/2000/svg" width="115.68559"
                        height="88.29441" viewBox="0,0,115.68559,88.29441">
                        <g transform="translate(-191.87889,-75.62023)">
                            <g stroke-miterlimit="10">
                                <path d="" fill="#FACC15" stroke="none" stroke-width="0.5" stroke-linecap="butt"></path>
                                <path
                                    d="M195.12889,128.77752c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,0.60102 -9.22352,20.49284 -9.22352,20.49284l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z"
                                    fill="none" stroke="#FACC15" stroke-width="6" stroke-linecap="butt"></path>
                                <path
                                    d="M195.31785,124.43649c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,1.03481 -0.08666,2.8866 -0.08666,2.8866c0,0 16.8538,15.99287 16.21847,17.23929c-0.66726,1.30905 -23.05667,-4.14265 -23.05667,-4.14265l-2.29866,4.5096l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z"
                                    fill="#0F3A52" stroke-opacity="0.48627" stroke="#FACC15" stroke-width="0"
                                    stroke-linecap="butt"></path>
                                <path d="M271.10348,122.46768l10.06374,-3.28166l24.06547,24.28424" fill="none"
                                    stroke="#FACC15" stroke-width="6" stroke-linecap="round"></path>
                                <path d="M306.56448,144.85764l-41.62024,-8.16845l2.44004,-7.87698" fill="none"
                                    stroke="#000000" stroke-width="3.5" stroke-linecap="round"></path>
                                <path
                                    d="M276.02738,115.72434c-0.66448,-4.64715 2.56411,-8.95308 7.21127,-9.61756c4.64715,-0.66448 8.95309,2.56411 9.61757,7.21126c0.46467,3.24972 -1.94776,8.02206 -5.96624,9.09336c-2.11289,-1.73012 -5.08673,-5.03426 -5.08673,-5.03426c0,0 -4.12095,1.16329 -4.60481,1.54229c-0.16433,-0.04891 -0.62732,-0.38126 -0.72803,-0.61269c-0.30602,-0.70328 -0.36302,-2.02286 -0.44303,-2.58239z"
                                    fill="#ffffff" stroke="none" stroke-width="0.5" stroke-linecap="butt"></path>
                                <path
                                    d="M242.49281,125.6424c0,-4.69442 3.80558,-8.5 8.5,-8.5c4.69442,0 8.5,3.80558 8.5,8.5c0,4.69442 -3.80558,8.5 -8.5,8.5c-4.69442,0 -8.5,-3.80558 -8.5,-8.5z"
                                    fill="#ffffff" stroke="none" stroke-width="0.5" stroke-linecap="butt"></path>
                                <path d="" fill-opacity="0.26667" fill="#FACC15" stroke-opacity="0.48627"
                                    stroke="#ffffff" stroke-width="0" stroke-linecap="butt"></path>
                            </g>
                        </g>
                    </svg>
                </div>

                <svg id="gnd" version="1.1" xmlns="http://www.w3.org/2000/svg" width="475" height="530"
                    viewBox="0,0,163.40011,85.20095">
                    <g transform="translate(-176.25,-207.64957)">
                        <g stroke="#0F3A52" stroke-width="5" stroke-linecap="round" stroke-miterlimit="10" opacity="0.55">
                            <path
                                d="M295.5,273.1829c0,0 -57.38915,6.69521 -76.94095,-9.01465c-13.65063,-10.50609 15.70098,-20.69467 -2.5451,-19.94465c-30.31027,2.05753 -38.51396,-26.84135 -38.51396,-26.84135c0,0 6.50084,13.30023 18.93224,19.17888c9.53286,4.50796 26.23632,-1.02541 32.09529,4.95137c3.62417,3.69704 2.8012,6.33005 0.66517,8.49452c-3.79415,3.84467 -11.7312,6.21103 -6.24682,10.43645c22.01082,16.95812 72.55412,12.73944 72.55412,12.73944z"
                                fill="#0F3A52"></path>
                            <path
                                d="M338.92138,217.76285c0,0 -17.49626,12.55408 -45.36424,10.00353c-8.39872,-0.76867 -17.29557,-6.23066 -17.29557,-6.23066c0,0 3.06461,-2.23972 15.41857,0.72484c26.30467,6.31228 47.24124,-4.49771 47.24124,-4.49771z"
                                fill="#0F3A52"></path>
                            <path d="M209.14443,223.00182l1.34223,15.4356l-10.0667,-15.4356" fill="none"></path>
                            <path d="M198.20391,230.41806l12.95386,7.34824l6.71113,-12.08004" fill="none"></path>
                            <path d="M211.19621,238.53825l8.5262,-6.09014" fill="none"></path>
                            <path d="M317.57068,215.80173l5.27812,6.49615l0.40601,-13.39831" fill="none"></path>
                            <path d="M323.66082,222.70389l6.09014,-9.33822" fill="none"></path>
                        </g>
                    </g>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <div class="sw-loader-title">Steam<span>Wish</span></div>

        {{-- Progress bar --}}
        <div class="sw-progress-track">
            <div class="sw-progress-fill" id="sw-progress-fill"></div>
        </div>
    </div>

    <script>
        (function() {
            const overlay = document.getElementById('sw-loader-overlay');
            if (!overlay) return;

            // Show every time user visits the home page in this session
            const DURATION = 3000; // ms

            // Kick off progress bar
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    const fill = document.getElementById('sw-progress-fill');
                    if (fill) fill.style.width = '100%';
                });
            });

            // Fade out after DURATION
            setTimeout(function() {
                overlay.classList.add('fade-out');
                // Remove from DOM after transition ends
                overlay.addEventListener('transitionend', function() {
                    overlay.remove();
                }, {
                    once: true
                });
            }, DURATION);
        })();
    </script>
@endpush

@section('content')

    {{-- ══════════════════════════════════════ --}}
    {{--  HERO                                  --}}
    {{-- ══════════════════════════════════════ --}}
    <section id="hero" class="relative border-b-4 border-black overflow-hidden bg-[#0F3A52]"
        style="min-height:220px;">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col gap-4">
            <span
                class="bg-[#FACC15] border-2 border-black text-black font-black text-xs px-3 py-1 uppercase tracking-widest self-start">
                🎮 Tu wishlist inteligente
            </span>
            <h1 class="font-black text-4xl sm:text-6xl uppercase tracking-tight leading-none text-white">
                Domina<br><span class="text-[#FACC15]">Steam</span>
            </h1>
            <p class="text-blue-200 text-sm max-w-md leading-relaxed">
                Rastrea precios, descubre ofertas y gestiona tu wishlist en un solo lugar.
            </p>
            <div>
                <a href="{{ route('search') }}" id="hero-explore-btn"
                    class="inline-flex items-center gap-2 bg-[#FACC15] border-4 border-black text-black font-black uppercase text-sm px-6 py-3
                          shadow-[4px_4px_0_0_#000] hover:shadow-[2px_2px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                    <i data-lucide="search" class="w-4 h-4"></i> Explorar juegos
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════ --}}
    {{--  DASHBOARD                             --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- TOP: Two columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- LEFT: Most Played --}}
            <section id="most-played-section">
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#0F3A52]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Most Played</h2>
                    <span
                        class="bg-[#FACC15] border-2 border-black text-black text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Live</span>
                </div>
                {{-- Skeleton --}}
                <div id="most-played-skeleton" class="flex flex-col gap-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#0F3A52]">
                            <div class="w-8 h-8 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="w-28 h-16 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="flex-1 h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="w-14 h-6 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                        </div>
                    @endfor
                </div>
                {{-- Real data --}}
                <div id="most-played-list" class="flex flex-col gap-3 hidden"></div>
                {{-- Empty state --}}
                <div id="most-played-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

            {{-- RIGHT: Trending --}}
            <section id="trending-section">
                <div
                    class="flex items-center justify-between mb-4 border-b-4 border-black pb-2 bg-white px-4 py-2 shadow-[4px_4px_0_0_#16A34A]">
                    <h2 class="font-black text-xl uppercase text-[#0F3A52]">Trending</h2>
                    <span
                        class="bg-[#16A34A] border-2 border-black text-white text-xs font-black uppercase px-2 py-1 shadow-[2px_2px_0_0_#000]">Hot
                        🔥</span>
                </div>
                {{-- Skeleton --}}
                <div id="trending-skeleton" class="flex flex-col gap-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="flex items-center gap-4 bg-white border-4 border-black p-3 shadow-[4px_4px_0_0_#16A34A]">
                            <div class="w-28 h-16 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                            <div class="flex-1 h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="w-14 h-6 bg-gray-200 animate-pulse border-2 border-black shrink-0"></div>
                        </div>
                    @endfor
                </div>
                {{-- Real data --}}
                <div id="trending-list" class="flex flex-col gap-3 hidden"></div>
                {{-- Empty state --}}
                <div id="trending-empty"
                    class="hidden p-6 bg-white border-4 border-black text-center font-bold text-gray-400">
                    <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                    No se pudo cargar. Intenta más tarde.
                </div>
            </section>

        </div>

        {{-- BOTTOM: Upcoming carousel --}}
        <section id="upcoming-section" class="pt-8 border-t-4 border-black border-dashed">
            <div
                class="flex items-center justify-between mb-6 bg-[#0F3A52] px-4 py-3 border-4 border-black shadow-[4px_4px_0_0_#000]">
                <h2 class="font-black text-2xl uppercase text-white">Upcoming Games</h2>
                <span class="text-blue-200 uppercase font-bold text-xs tracking-widest hidden sm:block">Próximos
                    lanzamientos</span>
            </div>

            {{-- Skeleton --}}
            <div id="upcoming-skeleton" class="flex gap-6 overflow-x-auto pb-4">
                @for ($i = 0; $i < 6; $i++)
                    <div class="shrink-0 w-72 bg-white border-4 border-black shadow-[4px_4px_0_0_#0F3A52]">
                        <div class="w-full aspect-[460/215] bg-gray-200 animate-pulse border-b-4 border-black"></div>
                        <div class="p-3 flex flex-col gap-2">
                            <div class="h-4 bg-gray-200 animate-pulse rounded"></div>
                            <div class="h-4 w-2/3 bg-gray-200 animate-pulse rounded"></div>
                            <div class="h-5 w-16 bg-gray-200 animate-pulse border-2 border-black mt-1"></div>
                        </div>
                    </div>
                @endfor
            </div>
            {{-- Real data --}}
            <div id="upcoming-list" class="flex gap-6 overflow-x-auto pb-4 hide-scrollbar hidden"></div>
            {{-- Empty state --}}
            <div id="upcoming-empty"
                class="hidden p-6 bg-white border-4 border-black font-bold text-gray-400 text-center">
                No se pudo cargar. Intenta más tarde.
            </div>
        </section>

    </div>

@endsection

@push('scripts')
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        // ─────────────────────────────────────────────
        // Helpers
        // ─────────────────────────────────────────────
        function show(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function hide(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        function priceTag(game, accentClass = 'bg-[#FACC15] text-black') {
            const label = game.discount > 0 ?
                `-${game.discount}% · ${game.price}` :
                game.price;
            return `<span class="shrink-0 ${accentClass} border-2 border-black px-2 py-0.5 font-black text-xs">${label}</span>`;
        }

        function gameUrl(appid) {
            return `/game?appid=${appid}`;
        }

        // Shared set of saved appids (populated after loading)
        let savedAppids = new Set();

        function heartBtn(appid, classes = 'absolute top-2 right-2') {
            const saved = savedAppids.has(Number(appid));
            const active = saved ? 'bg-[#FACC15] text-black' : 'bg-white text-[#0F3A52]';
            return `<button
            data-appid="${appid}"
            class="wishlist-btn shrink-0 w-8 h-8 border-2 border-black flex items-center justify-center
                   shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px]
                   transition-all ${active} ${classes}"
            aria-label="Toggle wishlist">
            <i data-lucide="heart" class="w-3.5 h-3.5"></i>
        </button>`;
        }

        // ─────────────────────────────────────────────
        // Render: Most Played list item
        // ─────────────────────────────────────────────
        function renderMostPlayed(games) {
            const container = document.getElementById('most-played-list');
            if (!games.length) {
                show('most-played-empty');
                return;
            }

            container.innerHTML = games.map((g, i) => {
                const rank = i + 1;
                const badgeCls = rank <= 3 ? 'bg-[#FACC15] text-black' : 'bg-[#0F3A52] text-white';
                const thumb = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
                    `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                <span class="shrink-0 w-8 h-8 flex items-center justify-center border-2 border-black font-black text-sm ${badgeCls}">#${rank}</span>
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${priceTag(g)}
                ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
            </a>`;
            }).join('');

            hide('most-played-skeleton');
            show('most-played-list');
            lucide.createIcons();
        }

        // ─────────────────────────────────────────────
        // Render: Trending list item
        // ─────────────────────────────────────────────
        function renderTrending(games) {
            const container = document.getElementById('trending-list');
            if (!games.length) {
                show('trending-empty');
                return;
            }

            container.innerHTML = games.map(g => {
                const thumb = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="shrink-0 w-28 h-16 object-cover border-2 border-black">` :
                    `<div class="shrink-0 w-28 h-16 bg-gray-200 border-2 border-black"></div>`;

                const badge = g.discount > 0 ?
                    `<span class="shrink-0 bg-[#16A34A] border-2 border-black text-white font-black text-xs px-2 py-0.5">-${g.discount}% · ${g.price}</span>` :
                    `<span class="shrink-0 bg-[#FACC15] border-2 border-black text-black font-black text-xs px-2 py-0.5">${g.price}</span>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative flex items-center gap-4 bg-white border-4 border-black p-3
                      shadow-[4px_4px_0_0_#16A34A] hover:shadow-[6px_6px_0_0_#5DA9D6]
                      hover:-translate-x-1 hover:-translate-y-1 transition-all group">
                ${thumb}
                <span class="flex-1 font-black text-sm uppercase text-[#0F3A52] truncate group-hover:text-[#5DA9D6] transition-colors">${g.name}</span>
                ${badge}
                ${heartBtn(g.appid, 'relative shrink-0 ml-1')}
            </a>`;
            }).join('');

            hide('trending-skeleton');
            show('trending-list');
        }

        // ─────────────────────────────────────────────
        // Render: Upcoming cards carousel
        // ─────────────────────────────────────────────
        function renderUpcoming(games) {
            const container = document.getElementById('upcoming-list');
            if (!games.length) {
                show('upcoming-empty');
                return;
            }

            container.innerHTML = games.map(g => {
                const img = g.image ?
                    `<img src="${g.image}" alt="${g.name}" class="w-full aspect-[460/215] object-cover border-b-4 border-black">` :
                    `<div class="w-full aspect-[460/215] bg-[#0F3A52] border-b-4 border-black flex items-center justify-center">
                       <i data-lucide="calendar" class="w-10 h-10 text-[#5DA9D6]"></i>
                   </div>`;

                return `
            <a href="${gameUrl(g.appid)}"
               class="relative group block w-72 shrink-0 bg-white border-4 border-black
                      shadow-[4px_4px_0_0_#0F3A52] hover:shadow-[8px_8px_0_0_#5DA9D6]
                      hover:-translate-y-2 hover:-translate-x-1 transition-all">
                ${img}
                ${heartBtn(g.appid, 'absolute top-2 right-2')}
                <div class="p-3 flex flex-col gap-1">
                    <h3 class="font-black text-sm uppercase text-[#0F3A52] line-clamp-2 group-hover:text-[#5DA9D6] transition-colors leading-tight">${g.name}</h3>
                    <div class="flex items-center justify-between mt-1">
                        ${priceTag(g)}
                        <span class="text-gray-400 text-xs font-bold uppercase">Coming Soon</span>
                    </div>
                </div>
            </a>`;
            }).join('');

            hide('upcoming-skeleton');
            show('upcoming-list');
            lucide.createIcons();
        }

        // ─────────────────────────────────────────────
        // Fetch and render all sections
        // ─────────────────────────────────────────────
        async function loadHomeData() {
            try {
                // Load wishlist IDs and home data in parallel, but handle ID errors gracefully
                const homePromise = fetch('{{ route('api.home-data') }}').then(r => r.json());
                const idsPromise = fetch('{{ route('api.wishlist-ids') }}')
                    .then(r => r.ok ? r.json() : [])
                    .catch(() => []);

                const [data, ids] = await Promise.all([homePromise, idsPromise]);

                // Populate the shared set before rendering so heartBtn() reads correct state
                savedAppids = new Set(ids.map(Number));

                renderMostPlayed(data.mostPlayed ?? []);
                renderTrending(data.trending ?? []);
                renderUpcoming(data.upcoming ?? []);
            } catch (err) {
                console.error('Home data load failed:', err);
                ['most-played', 'trending', 'upcoming'].forEach(key => {
                    hide(`${key}-skeleton`);
                    show(`${key}-empty`);
                });
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', loadHomeData);
    </script>
@endpush
