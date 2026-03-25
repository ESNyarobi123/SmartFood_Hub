@extends('layouts.app')

@section('title', 'Monana Food — Agiza Chakula')

@section('content')
<!-- Hero Banner -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#0f0f0f] via-[#1a1a1a] to-[#0a1f18]"></div>
    <div class="absolute inset-0 opacity-10" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=30');background-size:cover;background-position:center"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-transparent to-[#0f0f0f]/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 mb-4 text-xs font-bold tracking-wider uppercase rounded-full bg-[#00d4aa]/15 text-[#00d4aa] border border-[#00d4aa]/20">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00d4aa] opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-[#00d4aa]"></span></span>
                Monana Food (Cyber)
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-[1.1] mb-4">
                Chakula Kitamu<br><span class="text-[#00d4aa]">Kilichopikwa Tayari</span>
            </h1>
            <p class="text-base sm:text-lg text-[#9ca3af] mb-8 max-w-lg leading-relaxed">Chagua muda wa mlo unaotaka — Asubuhi, Mchana, au Jioni — na uagize mlo wako ukifika mlangoni.</p>
            <div class="flex flex-wrap gap-3">
                @foreach($mealSlots as $slotData)
                    @if($slotData['is_open'])
                        <a href="{{ route('cyber.menu', ['slot' => $slotData['slot']->id]) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-bold rounded-xl transition-all text-sm shadow-lg shadow-[#00d4aa]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                            Agiza {{ $slotData['slot']->display_name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Meal Slot Cards -->
    <div class="mb-12 sm:mb-16" x-data="{ loaded: false }" x-init="$nextTick(() => { setTimeout(() => loaded = true, 200) })">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-white">Muda wa Mlo</h2>
                <p class="text-sm text-[#6b6b6b] mt-1">Chagua slot inayofaa na uagize</p>
            </div>
        </div>

        <!-- Skeleton -->
        <div x-show="!loaded" class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            @for($i = 0; $i < 3; $i++)
            <div class="card p-5 sm:p-6 space-y-4">
                <div class="flex justify-between"><div class="skeleton h-6 w-28 rounded"></div><div class="skeleton h-6 w-16 rounded-full"></div></div>
                <div class="skeleton h-4 w-full rounded"></div>
                <div class="space-y-2"><div class="skeleton h-3 w-full rounded"></div><div class="skeleton h-3 w-2/3 rounded"></div></div>
                <div class="skeleton h-11 w-full rounded-lg"></div>
            </div>
            @endfor
        </div>

        <!-- Actual Slot Cards -->
        <div x-show="loaded" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($mealSlots as $slotData)
                <div class="card p-5 sm:p-6 group hover:border-[#00d4aa]/30 transition-all duration-300 {{ $slotData['is_open'] ? 'ring-1 ring-[#00d4aa]/20' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg sm:text-xl font-bold text-white">{{ $slotData['slot']->display_name }}</h3>
                        @if($slotData['is_open'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/15 border border-green-500/30 rounded-full text-xs font-bold text-green-400">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                                OPEN
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-500/10 border border-red-500/20 rounded-full text-xs font-bold text-red-400">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                CLOSED
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-[#9ca3af] mb-4 line-clamp-2">{{ $slotData['slot']->description }}</p>

                    <div class="space-y-2 text-sm mb-5 bg-[#1a1a1a] rounded-lg p-3">
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Delivery</span>
                            <span class="text-white font-medium">{{ $slotData['slot']->delivery_time_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Cutoff</span>
                            <span class="text-white font-medium">{{ $slotData['cutoff_time'] }}</span>
                        </div>
                        @if($slotData['is_open'] && $slotData['time_remaining'])
                            <div class="flex justify-between items-center pt-1 border-t border-[#333]">
                                <span class="text-[#6b6b6b]">Inafungwa</span>
                                <span class="text-[#00d4aa] font-mono font-bold text-base" x-data="countdown('{{ $slotData['time_remaining'] }}')" x-text="display"></span>
                            </div>
                        @endif
                    </div>

                    @if($slotData['is_open'])
                        <a href="{{ route('cyber.menu', ['slot' => $slotData['slot']->id]) }}"
                           class="flex items-center justify-center gap-2 w-full py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-bold rounded-xl transition-all shadow-lg shadow-[#00d4aa]/10 group-hover:shadow-[#00d4aa]/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            Agiza Sasa
                        </a>
                    @else
                        <button disabled class="w-full py-3 bg-[#2a2a2a] text-[#555] font-bold rounded-xl cursor-not-allowed border border-[#333]">
                            Imefungwa
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Menu Items Preview -->
    @if($featuredItems->isNotEmpty())
    <div class="mb-12 sm:mb-16">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-white">Vyakula Maarufu</h2>
                <p class="text-sm text-[#6b6b6b] mt-1">Preview ya menu yetu — agiza kupitia slot yoyote iliyo wazi</p>
            </div>
            <a href="{{ route('cyber.menu') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-[#00d4aa] hover:text-[#00b894] transition-colors">
                Menyu Yote
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        @php
            $cyberFallbacks = [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1567620905732-2d3e1b311543?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=400&h=300&fit=crop&q=75',
            ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($featuredItems as $idx => $item)
            <div class="card overflow-hidden group hover:border-[#00d4aa]/30 transition-all duration-300">
                <div class="aspect-[4/3] bg-[#2a2a2a] relative overflow-hidden">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : $cyberFallbacks[$idx % count($cyberFallbacks)] }}"
                         alt="{{ $item->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ $cyberFallbacks[$idx % count($cyberFallbacks)] }}'">
                    <div class="absolute top-2 right-2 px-2 py-1 bg-[#00d4aa] text-black text-xs font-bold rounded-md shadow">TZS {{ number_format($item->price, 0) }}</div>
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="font-bold text-white text-sm sm:text-base truncate">{{ $item->name }}</h3>
                    <p class="text-xs text-[#6b6b6b] mt-1 line-clamp-2">{{ Str::limit($item->description, 50) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('cyber.menu') }}" class="sm:hidden mt-4 flex items-center justify-center gap-2 w-full py-3 bg-[#242424] hover:bg-[#333] text-[#00d4aa] font-bold rounded-xl border border-[#333] transition-colors text-sm">
            Angalia Menyu Yote
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    @endif

    <!-- How it Works -->
    <div class="card p-6 sm:p-8 lg:p-10 bg-gradient-to-br from-[#242424] to-[#1a1a1a]">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 text-center">Jinsi Inavyofanya Kazi</h2>
        <p class="text-sm text-[#6b6b6b] text-center mb-8">Hatua 4 rahisi tu</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="text-center group">
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 bg-[#00d4aa]/10 border border-[#00d4aa]/20 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-[#00d4aa] text-black text-[10px] font-black rounded-full flex items-center justify-center shadow">1</span>
                </div>
                <h3 class="font-bold text-white text-sm mb-1">Chagua Muda</h3>
                <p class="text-xs text-[#6b6b6b]">Asubuhi, Mchana, Usiku</p>
            </div>
            <div class="text-center group">
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 bg-[#00d4aa]/10 border border-[#00d4aa]/20 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-[#00d4aa] text-black text-[10px] font-black rounded-full flex items-center justify-center shadow">2</span>
                </div>
                <h3 class="font-bold text-white text-sm mb-1">Chagua Chakula</h3>
                <p class="text-xs text-[#6b6b6b]">Angalia menu & uchague</p>
            </div>
            <div class="text-center group">
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 bg-[#00d4aa]/10 border border-[#00d4aa]/20 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-[#00d4aa] text-black text-[10px] font-black rounded-full flex items-center justify-center shadow">3</span>
                </div>
                <h3 class="font-bold text-white text-sm mb-1">Lipia</h3>
                <p class="text-xs text-[#6b6b6b]">ZenoPay / Mobile Money</p>
            </div>
            <div class="text-center group">
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 bg-[#00d4aa]/10 border border-[#00d4aa]/20 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-[#00d4aa] text-black text-[10px] font-black rounded-full flex items-center justify-center shadow">4</span>
                </div>
                <h3 class="font-bold text-white text-sm mb-1">Pokea Mlangoni</h3>
                <p class="text-xs text-[#6b6b6b]">Delivery ya haraka</p>
            </div>
        </div>
    </div>
</div>

<!-- Live Countdown Alpine Component -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', (timeStr) => {
        const parts = timeStr.split(':').map(Number);
        let total = (parts[0] || 0) * 3600 + (parts[1] || 0) * 60 + (parts[2] || 0);
        return {
            display: timeStr,
            init() {
                this.tick();
                this.interval = setInterval(() => this.tick(), 1000);
            },
            tick() {
                if (total <= 0) { clearInterval(this.interval); this.display = '00:00:00'; return; }
                total--;
                const h = String(Math.floor(total / 3600)).padStart(2, '0');
                const m = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
                const s = String(total % 60).padStart(2, '0');
                this.display = `${h}:${m}:${s}`;
            },
            destroy() { clearInterval(this.interval); }
        };
    });
});
</script>
@endsection
