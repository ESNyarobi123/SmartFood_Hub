@extends('layouts.app')

@section('title', 'Monana — Chakula & Vifurushi vya Jikoni')

@section('content')
<!-- Hero Section — Split Focus -->
<header class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-white to-amber-50 pt-10 sm:pt-16">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=%270 0 60 60%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cg fill=%27none%27 fill-rule=%27evenodd%27%3E%3Cg fill=%27%239C92AC%27 fill-opacity=%271%27%3E%3Cpath d=%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 lg:py-24 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <!-- Left: Copy -->
            <div class="text-center lg:text-left order-2 lg:order-1">
                <span class="inline-block px-3 py-1 mb-4 text-xs font-bold tracking-wider uppercase rounded-full bg-amber-100 text-amber-700">Dar es Salaam's #1 Food Platform</span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.5rem] font-black text-gray-900 leading-[1.1] mb-5">
                    Chakula Kitamu au Vifurushi vya Jikoni — <span class="bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent">Vyote Mlangoni Kwako.</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">Agiza mlo wa leo au jiandikishe kupokea bidhaa za jikoni kila wiki. Haraka, safi, bei nafuu.</p>

                <!-- Dual CTAs -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                    <a href="{{ route('cyber.index') }}" class="group inline-flex items-center justify-center gap-2 px-6 py-3.5 sm:px-8 sm:py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/25 hover:shadow-xl hover:shadow-orange-500/30 transition-all text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                        Agiza Chakula
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="{{ route('food.index') }}" class="group inline-flex items-center justify-center gap-2 px-6 py-3.5 sm:px-8 sm:py-4 bg-white text-emerald-700 font-bold rounded-xl border-2 border-emerald-200 hover:border-emerald-400 hover:bg-emerald-50 shadow-lg shadow-emerald-500/10 transition-all text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Vifurushi vya Sokoni
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="flex items-center justify-center lg:justify-start gap-6 mt-8 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Delivery Bure</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> ZenoPay</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Haraka Sana</span>
                </div>
            </div>

            <!-- Right: Hero Visual — Split Image -->
            <div class="relative order-1 lg:order-2 flex justify-center">
                <div class="relative w-full max-w-md lg:max-w-lg">
                    <!-- Food image -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-orange-500/10">
                        <img src="https://images.unsplash.com/photo-1604329760661-e71dc83f8f26?w=600&h=400&fit=crop&q=80" alt="Chakula kitamu cha Tanzania" class="w-full h-56 sm:h-72 lg:h-80 object-cover" loading="lazy" onerror="this.parentElement.classList.add('bg-gradient-to-br','from-amber-100','to-orange-100');this.style.display='none'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur text-amber-700 text-xs font-bold rounded-lg">Monana Food</span>
                            <span class="px-3 py-1.5 bg-emerald-500/90 backdrop-blur text-white text-xs font-bold rounded-lg">Monana Market</span>
                        </div>
                    </div>
                    <!-- Floating badge -->
                    <div class="absolute -bottom-4 -right-2 sm:-right-4 bg-white rounded-xl shadow-xl p-3 border border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                            <div><p class="text-xs font-bold text-gray-900">500+ Orders</p><p class="text-[10px] text-gray-500">Delivered this month</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- How It Works — 3 Steps -->
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider uppercase rounded-full bg-gray-100 text-gray-600">Inavyofanya Kazi</span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900">Hatua 3 Rahisi</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-6 lg:gap-10">
            <!-- Step 1 -->
            <div class="text-center group">
                <div class="relative mx-auto mb-5 w-16 h-16 sm:w-20 sm:h-20 bg-amber-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-amber-500 text-white text-xs font-black rounded-full flex items-center justify-center shadow">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Chagua Huduma</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Chakula kilichopikwa (Cyber) au bidhaa za jikoni (Market)</p>
            </div>
            <!-- Step 2 -->
            <div class="text-center group">
                <div class="relative mx-auto mb-5 w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-emerald-500 text-white text-xs font-black rounded-full flex items-center justify-center shadow">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Lipia na ZenoPay</h3>
                <p class="text-sm text-gray-500 leading-relaxed">M-Pesa, Tigo Pesa, Airtel Money — malipo salama na haraka</p>
            </div>
            <!-- Step 3 -->
            <div class="text-center group">
                <div class="relative mx-auto mb-5 w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-blue-500 text-white text-xs font-black rounded-full flex items-center justify-center shadow">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Pokea Mlangoni</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Delivery ya haraka mpaka nyumbani au ofisini kwako</p>
            </div>
        </div>
    </div>
</section>

<!-- Service A: Monana Food — Live Meal Slots -->
<section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-amber-50/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider uppercase rounded-full bg-orange-100 text-orange-700">Monana Food (Cyber)</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900">Mlo wa Leo Uko Tayari</h2>
                <p class="text-sm sm:text-base text-gray-500 mt-2 max-w-lg">Chagua muda wa mlo unaotaka na uagize chakula kitamu moja kwa moja.</p>
            </div>
            <a href="{{ route('cyber.menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-amber-700 bg-amber-100 hover:bg-amber-200 rounded-lg transition-colors whitespace-nowrap">
                Angalia Menyu Yote
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Meal Slot Status Ribbon -->
        <div class="flex flex-wrap gap-2 sm:gap-3 mb-8">
            @foreach($mealSlots as $slotData)
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl border {{ $slotData['is_open'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    @if($slotData['is_open'])
                        <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                    @endif
                    <span class="text-sm font-semibold {{ $slotData['is_open'] ? 'text-green-800' : 'text-gray-600' }}">{{ $slotData['slot']->display_name }}</span>
                    @if($slotData['is_open'])
                        <span class="text-[10px] font-bold text-green-600 uppercase tracking-wide">Open</span>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Featured Meal Cards -->
        @if($featuredCyberItems->isNotEmpty())
        @php
            $fallbackImages = [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop&q=75',
                'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=400&h=300&fit=crop&q=75',
            ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($featuredCyberItems as $index => $item)
            <a href="{{ route('cyber.menu') }}" class="group bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300">
                <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : $fallbackImages[$index % count($fallbackImages)] }}"
                         alt="{{ $item->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ $fallbackImages[$index % count($fallbackImages)] }}'">
                    <div class="absolute top-2 right-2 px-2 py-1 bg-white/90 backdrop-blur text-amber-700 text-xs font-bold rounded-md shadow-sm">TZS {{ number_format($item->price, 0) }}</div>
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate">{{ $item->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($item->description, 60) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- Service B: Monana Market — Subscription Packages -->
<section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-emerald-50/40 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider uppercase rounded-full bg-emerald-100 text-emerald-700">Monana Market</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900">Vifurushi vya Jikoni</h2>
                <p class="text-sm sm:text-base text-gray-500 mt-2 max-w-lg">Jiandikishe kupata bidhaa za jikoni kila wiki au mwezi — bei nafuu, delivery bure.</p>
            </div>
            <a href="{{ route('food.packages') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition-colors whitespace-nowrap">
                Vifurushi Vyote
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($featuredPackages->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($featuredPackages as $index => $package)
            <div class="relative bg-white rounded-2xl border {{ $index === 0 ? 'border-emerald-300 ring-2 ring-emerald-100' : 'border-gray-200' }} shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                @if($index === 0)
                <div class="absolute top-0 right-0 px-3 py-1 bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider rounded-bl-xl">Popular</div>
                @endif
                <div class="p-5 sm:p-6 lg:p-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-1">{{ $package->name }}</h3>
                    <p class="text-sm text-gray-500 mb-5 line-clamp-2">{{ Str::limit($package->description, 80) }}</p>

                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-3xl sm:text-4xl font-black text-gray-900">TZS {{ number_format($package->base_price, 0) }}</span>
                        <span class="text-sm text-gray-400 font-medium">/{{ $package->duration_type }}</span>
                    </div>

                    <ul class="space-y-3 mb-7">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Siku {{ $package->duration_days }} za usajili
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Delivery {{ $package->deliveries_per_week }}x kwa wiki
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Chagua bidhaa unavyotaka
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Delivery bure
                        </li>
                    </ul>

                    <a href="{{ route('food.packages.show', $package) }}" class="block w-full text-center py-3 font-bold rounded-xl transition-all text-sm sm:text-base {{ $index === 0 ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-500/25' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                        Jisajili Sasa
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p class="text-gray-500 font-medium">Vifurushi vitakuja hivi karibuni</p>
        </div>
        @endif
    </div>
</section>

<!-- Trust & Social Proof -->
<section class="py-12 sm:py-16 bg-gray-900 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black">Watu Wanatusema Nini?</h2>
            <p class="text-sm sm:text-base text-gray-400 mt-2">Wateja wetu wanapenda huduma zetu</p>
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 mb-12">
            <div class="text-center p-4 sm:p-6 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-2xl sm:text-3xl font-black text-amber-400">500+</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Orders Delivered</p>
            </div>
            <div class="text-center p-4 sm:p-6 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-2xl sm:text-3xl font-black text-emerald-400">{{ $cyberItemsCount + $productsCount }}+</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Products Available</p>
            </div>
            <div class="text-center p-4 sm:p-6 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-2xl sm:text-3xl font-black text-blue-400">{{ $packagesCount }}</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Subscription Plans</p>
            </div>
            <div class="text-center p-4 sm:p-6 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-2xl sm:text-3xl font-black text-pink-400">4.8</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Customer Rating</p>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 sm:p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-300 leading-relaxed mb-4">"Chakula ni kitamu sana na delivery ni haraka! Nimefurahi sana na huduma ya Monana."</p>
                <p class="text-xs font-bold text-white">— Amina M., Kinondoni</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 sm:p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-300 leading-relaxed mb-4">"Package ya wiki inanisaidia sana kupanga jikoni. Bei nzuri na bidhaa fresh."</p>
                <p class="text-xs font-bold text-white">— Joseph K., Mikocheni</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 sm:p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-300 leading-relaxed mb-4">"Ni rahisi sana kuagiza na kulipa kwa simu. Monana ni juu!"</p>
                <p class="text-xs font-bold text-white">— Sarah L., Masaki</p>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-12 sm:py-20 bg-gradient-to-br from-amber-50 via-white to-emerald-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 mb-4">Tayari Kuanza?</h2>
        <p class="text-base sm:text-lg text-gray-500 mb-8 max-w-xl mx-auto">Jiunge na familia ya Monana leo na ufurahie huduma bora za chakula Dar es Salaam.</p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="{{ route('cyber.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/25 hover:shadow-xl transition-all text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                Agiza Chakula Sasa
            </a>
            <a href="{{ route('food.packages') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-xl transition-all text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Angalia Vifurushi
            </a>
        </div>
    </div>
</section>

<!-- WhatsApp Floating Action Button -->
<a href="https://wa.me/255700000000?text=Habari%2C%20nataka%20kuagiza%20chakula%20kupitia%20Monana" target="_blank" rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-[100] w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-xl shadow-green-500/30 flex items-center justify-center transition-all hover:scale-110"
   aria-label="Chat on WhatsApp">
    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
</a>
@endsection
