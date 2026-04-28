@extends('layouts.app')

@section('title', 'Notificaciones — SteamWish')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 border-b-4 border-black pb-4 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#FACC15] border-2 border-black flex items-center justify-center shadow-[2px_2px_0_0_#000] shrink-0">
                <i data-lucide="bell" class="w-5 h-5 text-black"></i>
            </div>
            <div>
                <h1 class="font-black text-3xl md:text-4xl uppercase text-[#0F3A52]">Notificaciones</h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Bajadas de precio en tu wishlist</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Botón marcar todo como leído --}}
            @if($notifications->where('read_at', null)->count() > 0)
            <button id="mark-all-read-btn"
                class="flex items-center gap-2 bg-[#0F3A52] text-white border-2 border-black font-black text-xs uppercase px-4 py-2
                       shadow-[3px_3px_0_0_#000] hover:shadow-[1px_1px_0_0_#000] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                <i data-lucide="check-check" class="w-4 h-4"></i>
                Marcar todo como leído
            </button>
            @endif

            {{-- Badge de no leídas --}}
            @php $unread = $notifications->where('read_at', null)->count(); @endphp
            @if($unread > 0)
            <span class="bg-red-500 text-white border-2 border-black font-black text-sm px-3 py-1 shadow-[2px_2px_0_0_#000]">
                {{ $unread }} sin leer
            </span>
            @endif
        </div>
    </div>

    {{-- Lista de notificaciones --}}
    @if($notifications->isEmpty())
        <div class="text-center bg-white border-4 border-black p-16 shadow-[8px_8px_0_0_#0F3A52]">
            <div class="w-20 h-20 bg-[#F5F5F5] border-4 border-black flex items-center justify-center mx-auto mb-6 shadow-[4px_4px_0_0_#000]">
                <i data-lucide="bell-off" class="w-10 h-10 text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-black uppercase text-[#0F3A52] mb-3">Sin notificaciones</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Cuando el precio de un juego en tu wishlist baje, te avisaremos aquí (y por email si lo tienes activado).
            </p>
            <a href="{{ route('wishlist.index') }}"
               class="inline-flex items-center gap-2 bg-[#FACC15] border-4 border-black font-black uppercase
                      px-6 py-3 shadow-[4px_4px_0_0_#000] hover:shadow-[2px_2px_0_0_#000]
                      hover:translate-x-[2px] hover:translate-y-[2px] transition-all text-black text-sm">
                <i data-lucide="heart" class="w-4 h-4"></i>
                Ver mi Wishlist
            </a>
        </div>
    @else
        <div class="flex flex-col gap-4" id="notifications-list">
            @foreach($notifications as $notification)
            <div id="notif-{{ $notification->id }}"
                 class="group bg-white border-4 border-black shadow-[4px_4px_0_0_{{ $notification->isUnread() ? '#0F3A52' : '#D1D5DB' }}]
                        hover:shadow-[6px_6px_0_0_#5DA9D6] hover:-translate-y-0.5 transition-all flex overflow-hidden
                        {{ $notification->isUnread() ? 'ring-2 ring-[#FACC15] ring-offset-0' : '' }}">

                {{-- Game image --}}
                @if($notification->game_image)
                <a href="/game?appid={{ $notification->appid }}" class="shrink-0 block w-36 sm:w-48 relative border-r-4 border-black overflow-hidden">
                    <img src="{{ $notification->game_image }}"
                         alt="{{ $notification->game_name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         onerror="this.src='https://placehold.co/192x96/0F3A52/5DA9D6?text=No+Image'">
                    @if($notification->isUnread())
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-black border border-black px-1.5 py-0.5 uppercase">
                        Nuevo
                    </span>
                    @endif
                </a>
                @endif

                {{-- Content --}}
                <div class="flex-1 p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="flex-1">
                        {{-- Unread dot --}}
                        <div class="flex items-center gap-2 mb-1">
                            @if($notification->isUnread())
                            <span class="w-2 h-2 bg-red-500 border border-black shrink-0"></span>
                            @endif
                            <a href="/game?appid={{ $notification->appid }}"
                               class="font-black text-sm uppercase text-[#0F3A52] hover:text-[#5DA9D6] transition-colors line-clamp-1">
                                {{ $notification->game_name }}
                            </a>
                        </div>

                        {{-- Price comparison --}}
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-gray-400 line-through text-sm font-bold">{{ $notification->old_price_formatted }}</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 text-gray-400"></i>
                            <span class="text-xl font-black text-[#0F3A52]">{{ $notification->new_price_formatted }}</span>
                            @if($notification->discount_percent > 0)
                            <span class="bg-[#FACC15] text-black text-xs font-black border-2 border-black px-2 py-0.5 shadow-[2px_2px_0_0_#000]">
                                -{{ $notification->discount_percent }}%
                            </span>
                            @endif
                        </div>

                        <p class="text-[11px] text-gray-400 font-bold uppercase">
                            <i data-lucide="clock" class="w-3 h-3 inline-block mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        <a href="https://store.steampowered.com/app/{{ $notification->appid }}"
                           target="_blank"
                           class="flex items-center gap-1 bg-[#FACC15] text-black border-2 border-black font-black text-[10px] uppercase
                                  px-3 py-1.5 shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                            Ver en Steam
                        </a>
                        @if($notification->isUnread())
                        <button
                            onclick="markAsRead({{ $notification->id }}, this)"
                            class="flex items-center gap-1 bg-white text-[#0F3A52] border-2 border-black font-black text-[10px] uppercase
                                   px-3 py-1.5 shadow-[2px_2px_0_0_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                            <i data-lucide="check" class="w-3 h-3"></i>
                            Marcar leído
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function markAsRead(id, btn) {
        fetch(`/api/notifications/mark-read/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).then(res => res.json()).then(data => {
            if (data.ok) {
                // Remove the "Nuevo" badge and unread styles
                const card = document.getElementById(`notif-${id}`);
                if (card) {
                    card.classList.remove('ring-2', 'ring-[#FACC15]');
                    // Update shadow colour
                    card.style.boxShadow = '4px 4px 0 0 #D1D5DB';
                }
                // Remove the button
                btn.closest('button')?.remove();
                // Update unread count in navbar badge
                const badge = document.getElementById('nav-notif-badge');
                if (badge) {
                    const current = parseInt(badge.textContent) || 0;
                    if (current - 1 <= 0) {
                        badge.style.display = 'none';
                    } else {
                        badge.textContent = current - 1;
                    }
                }
            }
        });
    }

    document.getElementById('mark-all-read-btn')?.addEventListener('click', function () {
        fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).then(res => res.json()).then(data => {
            if (data.ok) {
                // Hide unread badge in navbar
                const badge = document.getElementById('nav-notif-badge');
                if (badge) badge.style.display = 'none';
                // Reload page to reflect changes
                location.reload();
            }
        });
    });

</script>
@endpush
