@extends('layouts.app')

@section('title', 'Dashboard - SteamWish')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-section-title title="My Dashboard" subtitle="Welcome back, {{ Auth::user()->username }}" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            {{-- Profile Card --}}
            <div class="bg-white border-4 border-black nb-shadow p-6 flex flex-col items-center">
                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->username }}"
                    class="w-32 h-32 rounded-full border-4 border-black mb-4">
                <h2 class="text-2xl font-black text-[#0F3A52] uppercase mb-2">{{ Auth::user()->username }}</h2>
                <a href="{{ Auth::user()->profile_url }}" target="_blank"
                    class="bg-[#FACC15] border-2 border-black px-4 py-2 font-bold nb-shadow-sm hover:translate-y-1 hover:shadow-none transition-all">View
                    Steam Profile</a>
            </div>

            {{-- Stats and Wishlist --}}
            <div class="md:col-span-2 flex flex-col gap-8">
                <div class="bg-[#0F3A52] text-white border-4 border-black nb-shadow p-6">
                    <h3 class="text-xl font-bold text-[#FACC15] uppercase tracking-wider mb-4">Wishlist Stats</h3>
                    <p class="text-lg">You have <span
                            class="font-black text-2xl">{{ Auth::user()->wishlists()->count() }}</span> games in your
                        wishlist.</p>
                    <div class="mt-6">
                        <a href="{{ route('wishlist.index') }}"
                            class="bg-white text-black border-2 border-black px-6 py-2 font-bold inline-block hover:bg-[#FACC15] transition-colors">Manage
                            Wishlist</a>
                    </div>
                </div>

                <div class="bg-white border-4 border-black p-6 opacity-75">
                    <h3 class="text-xl font-bold uppercase mb-2 text-gray-500">Coming Soon: Deal Alerts</h3>
                    <p class="text-sm">We're working on a feature to email you when your wishlisted games go on sale. Stay
                        tuned!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
