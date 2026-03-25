@extends('layouts.app')

@section('title', 'Vifurushi — Monana Market')

@section('content')
<!-- Hero Banner -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#ff6b35]/15 via-[#1a1a1a] to-[#0d0d0d]"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-[#ff6b35]/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-56 h-56 bg-[#ff6b35]/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-10 sm:pb-14">
        <a href="{{ route('food.index') }}" class="text-sm text-[#a0a0a0] hover:text-white mb-5 inline-flex items-center transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Rudi Monana Market
        </a>
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#ff6b35]/10 border border-[#ff6b35]/20 rounded-full text-xs font-bold text-[#ff6b35] mb-4">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    Okoa Pesa na Subscription
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-3 leading-tight">Vifurushi vya<br><span class="text-[#ff6b35]">Monana Market</span></h1>
                <p class="text-sm sm:text-base text-[#9ca3af] leading-relaxed">Chagua package inayokufaa na upate bidhaa za jikoni kwa bei nafuu — delivery bure kwa kila package.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2 px-4 py-2.5 bg-green-500/10 border border-green-500/20 rounded-xl">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold text-green-400">Delivery Bure</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2.5 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span class="text-sm font-bold text-blue-400">Badilisha Wakati Wowote</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Strip -->
<div class="border-y border-[#2a2a2a] bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#ff6b35]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div><p class="text-xs font-bold text-white">Bei Nafuu</p><p class="text-[10px] text-[#6b6b6b]">Okoa hadi 30%</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <div><p class="text-xs font-bold text-white">Delivery Bure</p><p class="text-[10px] text-[#6b6b6b]">Kila delivery</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div><p class="text-xs font-bold text-white">Customize</p><p class="text-[10px] text-[#6b6b6b]">Badilisha bidhaa</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div><p class="text-xs font-bold text-white">Pause Anytime</p><p class="text-[10px] text-[#6b6b6b]">Simamisha wakati wowote</p></div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12" x-data="{ loaded: false, selectedFilter: 'all' }" x-init="$nextTick(() => { setTimeout(() => loaded = true, 200) })">

    <!-- Filter Tabs -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-wrap gap-2">
            <button @click="selectedFilter = 'all'" :class="selectedFilter === 'all' ? 'bg-[#ff6b35] text-white shadow-lg shadow-[#ff6b35]/20' : 'bg-[#2a2a2a] text-[#9ca3af] hover:bg-[#333] hover:text-white'" class="px-4 py-2 rounded-xl font-medium text-sm transition-all">
                Vifurushi Vyote
            </button>
            <button @click="selectedFilter = 'weekly'" :class="selectedFilter === 'weekly' ? 'bg-[#ff6b35] text-white shadow-lg shadow-[#ff6b35]/20' : 'bg-[#2a2a2a] text-[#9ca3af] hover:bg-[#333] hover:text-white'" class="px-4 py-2 rounded-xl font-medium text-sm transition-all">
                Kila Wiki
            </button>
            <button @click="selectedFilter = 'monthly'" :class="selectedFilter === 'monthly' ? 'bg-[#ff6b35] text-white shadow-lg shadow-[#ff6b35]/20' : 'bg-[#2a2a2a] text-[#9ca3af] hover:bg-[#333] hover:text-white'" class="px-4 py-2 rounded-xl font-medium text-sm transition-all">
                Kila Mwezi
            </button>
        </div>
    </div>

    <!-- Skeleton -->
    <div x-show="!loaded" class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
        @for($i = 0; $i < 3; $i++)
        <div class="card p-6 space-y-4 animate-pulse">
            <div class="skeleton h-6 w-32 rounded"></div>
            <div class="skeleton h-4 w-full rounded"></div>
            <div class="skeleton h-10 w-40 rounded"></div>
            <div class="space-y-2">
                <div class="skeleton h-3 w-full rounded"></div>
                <div class="skeleton h-3 w-3/4 rounded"></div>
                <div class="skeleton h-3 w-2/3 rounded"></div>
            </div>
            <div class="skeleton h-12 w-full rounded-xl"></div>
        </div>
        @endfor
    </div>

    <!-- Packages Grid -->
    <div x-show="loaded" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
        @forelse($packages as $package)
            <div class="card overflow-hidden group hover:shadow-2xl hover:shadow-[#ff6b35]/5 hover:-translate-y-1 transition-all duration-300 {{ $loop->index === 1 ? 'ring-2 ring-[#ff6b35]/60 relative' : 'hover:border-[#ff6b35]/30' }}"
                 x-show="selectedFilter === 'all' || selectedFilter === '{{ $package->duration_type }}'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                @if($loop->index === 1)
                    <div class="bg-gradient-to-r from-[#ff6b35] to-[#e55a2b] text-white text-center py-2 text-xs font-bold tracking-wider uppercase">
                        Inayopendwa Zaidi
                    </div>
                @endif
                <div class="p-5 sm:p-6 lg:p-7">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-white mb-1">{{ $package->name }}</h3>
                            <p class="text-xs sm:text-sm text-[#6b6b6b] line-clamp-2">{{ $package->description }}</p>
                        </div>
                        @if($loop->index === 1)
                        <div class="bg-yellow-400/10 text-yellow-400 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 flex-shrink-0">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Best
                        </div>
                        @endif
                    </div>

                    <div class="flex items-baseline gap-1 mb-6 mt-4">
                        <span class="text-3xl sm:text-4xl font-black text-white">TZS {{ number_format($package->base_price, 0) }}</span>
                        <span class="text-sm text-[#6b6b6b] font-medium">/{{ $package->duration_type }}</span>
                    </div>

                    <ul class="space-y-2.5 mb-6">
                        <li class="flex items-center gap-2.5 text-sm text-[#a0a0a0]">
                            <div class="w-5 h-5 bg-green-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Siku {{ $package->duration_days }} za usajili
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-[#a0a0a0]">
                            <div class="w-5 h-5 bg-green-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Delivery {{ $package->deliveries_per_week }}x kwa wiki
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-[#a0a0a0]">
                            <div class="w-5 h-5 bg-green-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Siku: {{ $package->getDeliveryDaysLabel() }}
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-[#a0a0a0]">
                            <div class="w-5 h-5 bg-green-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Chagua bidhaa unavyotaka
                        </li>
                    </ul>

                    <!-- Included Items -->
                    @if($package->items->isNotEmpty())
                    <div class="mb-6 p-3 sm:p-4 bg-[#1a1a1a] rounded-xl border border-[#2a2a2a]">
                        <h4 class="text-xs font-bold text-[#9ca3af] uppercase tracking-wider mb-3">Bidhaa Zilizomo:</h4>
                        <div class="space-y-1.5">
                            @foreach($package->items->take(5) as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-[#a0a0a0]">{{ $item->product->name }}</span>
                                    <span class="text-white font-medium">{{ $item->default_quantity }} {{ $item->product->unit }}</span>
                                </div>
                            @endforeach
                            @if($package->items->count() > 5)
                                <p class="text-xs text-[#555] pt-1">+{{ $package->items->count() - 5 }} bidhaa zaidi</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <a href="{{ route('food.packages.show', $package) }}"
                       class="group/btn flex items-center justify-center gap-2 w-full py-3 font-bold rounded-xl transition-all text-sm sm:text-base {{ $loop->index === 1 ? 'bg-[#ff6b35] hover:bg-[#e55a2b] text-white shadow-lg shadow-[#ff6b35]/20' : 'bg-[#2a2a2a] hover:bg-[#333] text-white border border-[#333]' }}">
                        Angalia Package
                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 card p-8 sm:p-12 text-center">
                <svg class="w-12 h-12 text-[#333] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <h3 class="text-lg font-bold text-white mb-2">Hakuna Vifurushi Kwa Sasa</h3>
                <p class="text-sm text-[#6b6b6b] mb-6">Vifurushi vitakuja hivi karibuni. Rudi tena!</p>
                <a href="{{ route('food.custom') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-bold rounded-xl transition-colors text-sm">
                    Agiza Custom Order
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </div>
        @endforelse
    </div>

    <!-- How It Works -->
    <div class="mt-12 sm:mt-16">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 text-center">Inavyofanya Kazi</h2>
        <p class="text-sm text-[#6b6b6b] text-center mb-8">Hatua 3 rahisi tu</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="card p-5 sm:p-6 text-center group hover:border-[#ff6b35]/30 transition-all relative overflow-hidden">
                <div class="absolute top-3 right-3 text-5xl font-black text-[#222] select-none">1</div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-[#ff6b35]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#ff6b35]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-1">Chagua Package</h3>
                    <p class="text-xs text-[#6b6b6b] leading-relaxed">Angalia vifurushi na uchague kinachokufaa</p>
                </div>
            </div>
            <div class="card p-5 sm:p-6 text-center group hover:border-[#ff6b35]/30 transition-all relative overflow-hidden">
                <div class="absolute top-3 right-3 text-5xl font-black text-[#222] select-none">2</div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-[#ff6b35]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#ff6b35]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-1">Lipa Mara Moja</h3>
                    <p class="text-xs text-[#6b6b6b] leading-relaxed">Lipa kupitia M-Pesa, Tigo Pesa au Airtel</p>
                </div>
            </div>
            <div class="card p-5 sm:p-6 text-center group hover:border-[#ff6b35]/30 transition-all relative overflow-hidden">
                <div class="absolute top-3 right-3 text-5xl font-black text-[#222] select-none">3</div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-[#ff6b35]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#ff6b35]/20 transition-colors">
                        <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-1">Pokea Delivery</h3>
                    <p class="text-xs text-[#6b6b6b] leading-relaxed">Bidhaa zitafika mlangoni kwako kwa wakati</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-12 sm:mt-16 card p-6 sm:p-8 lg:p-10 bg-gradient-to-br from-[#242424] to-[#1a1a1a]" x-data="{ openFaq: null }">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 text-center">Maswali Yanayoulizwa Sana</h2>
        <p class="text-sm text-[#6b6b6b] text-center mb-8">Jibu la swali lako linaweza kuwa hapa</p>

        <div class="max-w-2xl mx-auto space-y-3">
            <div class="border border-[#333] rounded-xl overflow-hidden hover:border-[#444] transition-colors">
                <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full flex items-center justify-between p-4 text-left hover:bg-[#2a2a2a] transition-colors">
                    <span class="text-sm font-bold text-white">Naweza kubadilisha bidhaa kwenye package?</span>
                    <svg :class="openFaq === 1 ? 'rotate-180' : ''" class="w-5 h-5 text-[#6b6b6b] transition-transform flex-shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openFaq === 1" x-collapse class="px-4 pb-4">
                    <p class="text-sm text-[#9ca3af] leading-relaxed">Ndiyo! Baada ya kujiandikisha, unaweza kubadilisha bidhaa na idadi yake kabla ya kila delivery. Utapata notification ya kukumbusha.</p>
                </div>
            </div>
            <div class="border border-[#333] rounded-xl overflow-hidden hover:border-[#444] transition-colors">
                <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full flex items-center justify-between p-4 text-left hover:bg-[#2a2a2a] transition-colors">
                    <span class="text-sm font-bold text-white">Delivery inafanywa siku gani?</span>
                    <svg :class="openFaq === 2 ? 'rotate-180' : ''" class="w-5 h-5 text-[#6b6b6b] transition-transform flex-shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openFaq === 2" x-collapse class="px-4 pb-4">
                    <p class="text-sm text-[#9ca3af] leading-relaxed">Siku za delivery zinategemea package uliyochagua. Kila package inaonyesha siku za delivery — kwa mfano Jumatatu na Alhamisi, au Jumatano na Jumamosi.</p>
                </div>
            </div>
            <div class="border border-[#333] rounded-xl overflow-hidden hover:border-[#444] transition-colors">
                <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full flex items-center justify-between p-4 text-left hover:bg-[#2a2a2a] transition-colors">
                    <span class="text-sm font-bold text-white">Naweza kusimamisha subscription?</span>
                    <svg :class="openFaq === 3 ? 'rotate-180' : ''" class="w-5 h-5 text-[#6b6b6b] transition-transform flex-shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openFaq === 3" x-collapse class="px-4 pb-4">
                    <p class="text-sm text-[#9ca3af] leading-relaxed">Ndiyo, unaweza kusimamisha (pause) subscription yako wakati wowote kupitia dashboard yako. Siku zilizobaki hazitapotea.</p>
                </div>
            </div>
            <div class="border border-[#333] rounded-xl overflow-hidden hover:border-[#444] transition-colors">
                <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full flex items-center justify-between p-4 text-left hover:bg-[#2a2a2a] transition-colors">
                    <span class="text-sm font-bold text-white">Nalipaje?</span>
                    <svg :class="openFaq === 4 ? 'rotate-180' : ''" class="w-5 h-5 text-[#6b6b6b] transition-transform flex-shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openFaq === 4" x-collapse class="px-4 pb-4">
                    <p class="text-sm text-[#9ca3af] leading-relaxed">Tunakubali malipo kupitia ZenoPay — M-Pesa, Tigo Pesa, na Airtel Money. Lipa mara moja unapojiandikisha na huduma itaanza mara moja.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="mt-10 sm:mt-14 text-center">
        <div class="card p-6 sm:p-8 bg-gradient-to-r from-[#ff6b35]/10 via-[#1a1a1a] to-[#ff6b35]/10 border-[#ff6b35]/20">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Hutaki Subscription?</h3>
            <p class="text-sm text-[#9ca3af] mb-5">Unaweza kuagiza bidhaa unavyohitaji moja kwa moja — bila kujiandikisha.</p>
            <a href="{{ route('food.custom') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold rounded-xl transition-all text-sm">
                Agiza Custom Order
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
        </div>
    </div>
</div>
@endsection
