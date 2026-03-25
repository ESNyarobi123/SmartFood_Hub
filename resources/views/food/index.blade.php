@extends('layouts.app')

@section('title', 'Monana Market — Bidhaa za Jikoni')

@section('content')
<!-- Hero Banner -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#0f0f0f] via-[#1a1a1a] to-[#1a0f05]"></div>
    <div class="absolute inset-0 opacity-10" style="background-image:url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200&q=30');background-size:cover;background-position:center"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-transparent to-[#0f0f0f]/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 mb-4 text-xs font-bold tracking-wider uppercase rounded-full bg-[#ff6b35]/15 text-[#ff6b35] border border-[#ff6b35]/20">
                Monana Market
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-[1.1] mb-4">
                Bidhaa za Jikoni<br><span class="text-[#ff6b35]">Hadi Mlangoni Kwako</span>
            </h1>
            <p class="text-base sm:text-lg text-[#9ca3af] mb-8 max-w-lg leading-relaxed">Jiandikishe kwa package ya kila wiki au agiza bidhaa unavyohitaji moja kwa moja. Bei nafuu, delivery bure.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('food.packages') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-bold rounded-xl transition-all text-sm shadow-lg shadow-[#ff6b35]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Angalia Vifurushi
                </a>
                <a href="{{ route('food.custom') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#333] hover:bg-[#444] text-white font-bold rounded-xl transition-all text-sm border border-[#444]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Custom Order
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Why Subscribe? Benefits Strip -->
<section class="bg-[#1a1a1a] border-y border-[#333] py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#ff6b35]/15 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"></path></svg>
                </div>
                <div><p class="text-sm font-bold text-white">Bei Nafuu</p><p class="text-xs text-[#6b6b6b]">Punguzo kubwa</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div><p class="text-sm font-bold text-white">Delivery Bure</p><p class="text-xs text-[#6b6b6b]">Kwa packages zote</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div><p class="text-sm font-bold text-white">Customize</p><p class="text-xs text-[#6b6b6b]">Badilisha bidhaa</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div><p class="text-sm font-bold text-white">Pause Anytime</p><p class="text-xs text-[#6b6b6b]">Simamisha wakati wowote</p></div>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ loaded: false }" x-init="$nextTick(() => { setTimeout(() => loaded = true, 200) })">
    <!-- Service Options -->
    <div class="mb-12 sm:mb-16">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-6">Chagua Huduma</h2>

        <!-- Skeleton -->
        <div x-show="!loaded" class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @for($i = 0; $i < 2; $i++)
            <div class="card p-6 sm:p-8 space-y-4">
                <div class="skeleton h-14 w-14 rounded-xl"></div>
                <div class="skeleton h-7 w-48 rounded"></div>
                <div class="skeleton h-4 w-full rounded"></div>
                <div class="space-y-2"><div class="skeleton h-3 w-3/4 rounded"></div><div class="skeleton h-3 w-2/3 rounded"></div><div class="skeleton h-3 w-1/2 rounded"></div></div>
            </div>
            @endfor
        </div>

        <!-- Actual Cards -->
        <div x-show="loaded" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <a href="{{ route('food.packages') }}" class="group">
                <div class="card p-6 sm:p-8 h-full hover:border-[#ff6b35]/40 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#ff6b35]/5 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#ff6b35]/15 border border-[#ff6b35]/20 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 group-hover:text-[#ff6b35] transition-colors">Subscription Packages</h2>
                        <p class="text-sm text-[#9ca3af] mb-5 leading-relaxed">Jiandikishe kwa package ya wiki au mwezi na upate bidhaa za jikoni kwa bei nafuu.</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Weekly & Monthly options</li>
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Customize items before delivery</li>
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Pause anytime</li>
                        </ul>
                        <span class="inline-flex items-center gap-2 text-[#ff6b35] font-bold text-sm group-hover:gap-3 transition-all">
                            View Packages <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </div>
                </div>
            </a>
            <a href="{{ route('food.custom') }}" class="group">
                <div class="card p-6 sm:p-8 h-full hover:border-indigo-500/40 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-10 -mt-10"></div>
                    <div class="relative">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-indigo-500/15 border border-indigo-500/20 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 group-hover:text-indigo-400 transition-colors">Custom Order</h2>
                        <p class="text-sm text-[#9ca3af] mb-5 leading-relaxed">Agiza bidhaa unavyohitaji bila kujiandikisha kwa package. Mara moja tu.</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Chagua bidhaa unavyotaka</li>
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Weka idadi halisi</li>
                            <li class="flex items-center gap-2 text-sm text-[#a0a0a0]"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Delivery moja tu</li>
                        </ul>
                        <span class="inline-flex items-center gap-2 text-indigo-400 font-bold text-sm group-hover:gap-3 transition-all">
                            Anza Kuagiza <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Products -->
    @if($products->isNotEmpty())
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-white">Bidhaa Maarufu</h2>
                <p class="text-sm text-[#6b6b6b] mt-1">Bidhaa zinazoagizwa zaidi na wateja wetu</p>
            </div>
            <a href="{{ route('food.custom') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-[#ff6b35] hover:text-[#e55a2b] transition-colors">
                Agiza Sasa <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        @php
            $productFallbacks = [
                'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1587486913049-53fc88980cfc?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1612257416648-ee7a6c5b4f9b?w=300&h=200&fit=crop&q=75',
                'https://images.unsplash.com/photo-1608198093002-ad4e005484ec?w=300&h=200&fit=crop&q=75',
            ];
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($products as $pIdx => $product)
            <div class="card overflow-hidden group hover:border-[#ff6b35]/30 transition-all duration-300">
                <div class="aspect-[3/2] bg-[#2a2a2a] relative overflow-hidden">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : $productFallbacks[$pIdx % count($productFallbacks)] }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ $productFallbacks[$pIdx % count($productFallbacks)] }}'">
                    <div class="absolute top-2 right-2 px-2 py-1 bg-[#ff6b35] text-white text-xs font-bold rounded-md shadow">TZS {{ number_format($product->price, 0) }}/{{ $product->unit }}</div>
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="font-bold text-white text-sm sm:text-base truncate">{{ $product->name }}</h3>
                    @if($product->description)
                        <p class="text-xs text-[#6b6b6b] mt-1 line-clamp-1">{{ Str::limit($product->description, 40) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('food.custom') }}" class="sm:hidden mt-4 flex items-center justify-center gap-2 w-full py-3 bg-[#242424] hover:bg-[#333] text-[#ff6b35] font-bold rounded-xl border border-[#333] transition-colors text-sm">
            Agiza Bidhaa Zote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    @endif
</div>
@endsection
