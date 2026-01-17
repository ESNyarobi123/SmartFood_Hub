@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section - Clean Landing Page Style -->
<section class="relative min-h-screen flex items-center bg-white overflow-hidden pt-8 sm:pt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-20 w-full">
        <!-- Hero Content Grid - Split Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left: Text Content -->
            <div class="text-center lg:text-left">
                <!-- Logo/Brand -->
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-3 sm:mb-4" style="font-family: 'Arial', sans-serif;">
                        <span class="text-red-600">Monana</span> <span class="text-gray-800">Platform</span>
                    </h1>
                </div>
                
                <!-- Main Headline -->
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                    Get Delicious Meals with Monana
                </h2>
                
                <!-- Sub-headline/Tagline -->
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-gray-800 mb-6 sm:mb-8 md:mb-10 leading-relaxed font-normal">
                    We bring the finest local restaurants right to your doorstep!
                    <br class="hidden sm:block">
                    <span class="text-red-600 font-semibold">Monana Food</span> - Fresh Meals | 
                    <span class="text-orange-500 font-semibold">Monana Market</span> - Kitchen Products
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                    <a href="{{ route('cyber.index') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base md:text-lg">
                        <span class="mr-2 text-base sm:text-lg">🍛</span>
                        <span class="whitespace-nowrap">Order Monana Food</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('food.index') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base md:text-lg">
                        <span class="mr-2 text-base sm:text-lg">🛒</span>
                        <span class="whitespace-nowrap">View Packages</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right: Hero Image -->
            <div class="relative flex justify-center lg:justify-end mt-8 lg:mt-0">
                <div class="relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl">
                    <img src="{{ asset('images/heromsosi.webp') }}" alt="Monana Food Hero" class="w-full h-auto rounded-xl sm:rounded-2xl shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Cards Section -->
<section class="py-8 sm:py-12 md:py-16 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
            <!-- Monana Food Card -->
            <a href="{{ route('cyber.index') }}" class="group bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-red-200">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl sm:text-3xl">🍛</span>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $cyberItemsCount ?? 0 }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Menu Items</p>
                        </div>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-red-600 transition-colors">Monana Food</h2>
                    <p class="text-sm sm:text-base text-gray-700 mb-4 sm:mb-6 leading-relaxed">Chakula kitamu kilichopikwa na upendo - Chagua muda wa mlo (Asubuhi, Mchana, Usiku) na uagize sasa!</p>

                    <!-- Meal Slots Status with Green for Open -->
                    <div class="space-y-2 mb-6">
                        @foreach($mealSlots as $slotData)
                            <div class="flex items-center justify-between p-3 rounded-lg border transition-all {{ $slotData['is_open'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-center space-x-3">
                                    @if($slotData['is_open'])
                                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                                    @else
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                    @endif
                                    <span class="text-sm font-semibold text-gray-900">{{ $slotData['slot']->display_name }}</span>
                                </div>
                                @if($slotData['is_open'])
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                        OPEN NOW
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">CLOSED</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <span class="text-red-600 font-bold flex items-center">
                            Order Now
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Monana Food Card -->
            <a href="{{ route('food.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-orange-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-3xl">🛒</span>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-900">{{ $packagesCount ?? 0 }}</p>
                            <p class="text-sm text-gray-500 font-medium">Packages</p>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-500 transition-colors">Monana Market</h2>
                    <p class="text-gray-700 mb-6 leading-relaxed">Bidhaa za jikoni za ubora wa juu - Mchele, Tambi, Mayai, na zaidi. Jiandikishe kwa package au agiza unavyotaka.</p>

                    <!-- Package Stats -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                            <span class="text-2xl block mb-2">📦</span>
                            <p class="text-xl font-bold text-gray-900">{{ $packagesCount ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-medium">Packages</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                            <span class="text-2xl block mb-2">🥘</span>
                            <p class="text-xl font-bold text-gray-900">{{ $productsCount ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-medium">Products</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <span class="text-orange-500 font-bold flex items-center">
                            View Packages
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-8 sm:py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Why Choose Monana?</h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-800 max-w-2xl mx-auto px-4">We offer the best food services at affordable prices with fast delivery</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-all duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">⏰</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Fast Delivery</h3>
                <p class="text-gray-700 leading-relaxed">We deliver your food on time - morning, afternoon, or evening. No delays!</p>
            </div>

            <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-all duration-300">
                <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">⭐</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">High Quality</h3>
                <p class="text-gray-700 leading-relaxed">Fresh products and food cooked with love and skill. Quality is our priority.</p>
            </div>

            <div class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-lg transition-all duration-300">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">💳</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Payment</h3>
                <p class="text-gray-700 leading-relaxed">Pay with M-Pesa, Tigo Pesa, or Airtel Money - fast, secure, and easy.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Packages -->
@if($featuredPackages->isNotEmpty())
<section class="py-8 sm:py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 sm:mb-12 gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 sm:mb-3">Subscription Packages</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-800">Subscribe and get kitchen products every week or month</p>
            </div>
            <a href="{{ route('food.packages') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-orange-500 text-white text-sm sm:text-base font-bold rounded-lg hover:bg-orange-600 transition-all shadow-lg">
                View All
                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            @foreach($featuredPackages as $package)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-orange-200">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $package->name }}</h3>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📦</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed">{{ Str::limit($package->description, 100) }}</p>

                        <div class="flex items-baseline mb-6">
                            <span class="text-3xl font-bold text-orange-500">TZS {{ number_format($package->base_price, 0) }}</span>
                            <span class="text-sm text-gray-600 ml-2">/{{ $package->duration_type }}</span>
                        </div>

                        <ul class="space-y-2 mb-8">
                            <li class="flex items-center text-sm text-gray-700">
                                <span class="text-green-500 mr-3">✓</span>
                                {{ $package->duration_days }} days subscription
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <span class="text-green-500 mr-3">✓</span>
                                {{ $package->deliveries_per_week }} deliveries per week
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <span class="text-green-500 mr-3">✓</span>
                                Fully customizable items
                            </li>
                        </ul>

                        <a href="{{ route('food.packages.show', $package) }}" class="block w-full text-center py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-600 transition-all shadow-lg">
                            View Package
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-8 sm:py-12 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6">Ready to Get Started?</h2>
        <p class="text-base sm:text-lg md:text-xl text-gray-800 mb-8 sm:mb-12 px-4">Sign up now and get the best food services in Tanzania</p>

        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center">
            <a href="{{ route('cyber.index') }}" class="inline-flex items-center justify-center px-10 py-5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                <span class="mr-2">🍛</span>
                Order Monana Food
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            <a href="{{ route('food.packages') }}" class="inline-flex items-center justify-center px-10 py-5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                <span class="mr-2">🛒</span>
                Subscribe to Food
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
@endsection
