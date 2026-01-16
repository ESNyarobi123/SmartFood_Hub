@extends('layouts.app')

@section('title', 'Monana Food')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Header -->
    <div class="text-center mb-6 sm:mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 sm:mb-3">Monana Food</h1>
        <p class="text-base sm:text-lg text-[#a0a0a0] max-w-2xl mx-auto">Jiandikishe kwa package au agiza unavyotaka</p>
    </div>

    <!-- Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-12 sm:mb-16">
        <!-- Packages Option -->
        <a href="{{ route('food.packages') }}" class="group">
            <div class="card p-6 sm:p-8 h-full glow-food transition-all duration-300 hover:border-[#ff6b35]/50">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-[#ff6b35]/20 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 sm:mb-3 group-hover:text-[#ff6b35] transition-colors">Subscription Packages</h2>
                <p class="text-sm sm:text-base text-[#a0a0a0] mb-4 sm:mb-6">Jiandikishe kwa package ya wiki au mwezi na upate bidhaa za jikoni kwa bei nafuu.</p>

                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Weekly & Monthly options
                    </li>
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Customize items before delivery
                    </li>
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Pause anytime
                    </li>
                </ul>

                <div class="flex items-center text-[#ff6b35] font-medium text-sm sm:text-base">
                    <span>View Packages</span>
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Custom Order Option -->
        <a href="{{ route('food.custom') }}" class="group">
            <div class="card p-6 sm:p-8 h-full transition-all duration-300 hover:border-[#6366f1]/50">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-indigo-500/20 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 sm:mb-3 group-hover:text-indigo-400 transition-colors">Custom Order</h2>
                <p class="text-sm sm:text-base text-[#a0a0a0] mb-4 sm:mb-6">Agiza bidhaa unavyohitaji bila kujiandikisha kwa package.</p>

                <ul class="space-y-1.5 sm:space-y-2 mb-4 sm:mb-6">
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Choose what you need
                    </li>
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Select exact quantities
                    </li>
                    <li class="flex items-center text-xs sm:text-sm text-[#a0a0a0]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        One-time delivery
                    </li>
                </ul>

                <div class="flex items-center text-indigo-400 font-medium text-sm sm:text-base">
                    <span>Start Shopping</span>
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Featured Products -->
    @if($products->isNotEmpty())
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-6">Bidhaa Maarufu</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <div class="card p-4">
                        <div class="w-12 h-12 bg-[#ff6b35]/20 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium text-white">{{ $product->name }}</h3>
                        <p class="text-sm text-[#ff6b35]">TZS {{ number_format($product->price, 0) }}/{{ $product->unit }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
