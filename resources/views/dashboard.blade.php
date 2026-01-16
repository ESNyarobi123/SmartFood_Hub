@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Welcome Header -->
    <div class="mb-6 sm:mb-8 lg:mb-12 relative">
        <div class="absolute inset-0 bg-gradient-to-r from-[#00ffc8]/10 via-[#8b5cf6]/10 to-[#ff7b54]/10 rounded-xl sm:rounded-2xl lg:rounded-3xl blur-2xl"></div>
        <div class="relative card p-4 sm:p-6 md:p-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl lg:rounded-3xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                <div class="flex-1 w-full sm:w-auto">
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-black text-white mb-1 sm:mb-2 md:mb-3">
                        Karibu, <span class="bg-gradient-to-r from-[#00ffc8] via-[#8b5cf6] to-[#ff7b54] bg-clip-text text-transparent">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-[#a8b2c1]">Overview ya huduma zako zote kwa Monana Platform</p>
                </div>
                <div class="hidden sm:flex flex-shrink-0">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 md:w-20 lg:w-24 md:h-20 lg:h-24 bg-gradient-to-br from-[#00ffc8] via-[#8b5cf6] to-[#ff7b54] rounded-xl sm:rounded-2xl lg:rounded-3xl flex items-center justify-center" style="box-shadow: 0 0 50px rgba(139, 92, 246, 0.5);">
                        <span class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8 lg:mb-12">
        <!-- Cyber Pending Orders -->
        <div class="group card p-4 sm:p-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl hover:border-[#00ffc8]/50 transition-all duration-300 hover:scale-105 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-[#00ffc8]/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-[#00ffc8] to-[#00d9f5] rounded-lg sm:rounded-xl flex items-center justify-center" style="box-shadow: 0 0 20px rgba(0, 255, 200, 0.4);">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold px-2 sm:px-3 py-1 bg-[#00ffc8]/20 text-[#00ffc8] rounded-full">CYBER</span>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-white mb-1 group-hover:text-[#00ffc8] transition-colors">{{ $cyberPendingOrders }}</p>
                <p class="text-xs sm:text-sm text-[#5c6b7f]">Pending Orders</p>
                <a href="{{ route('cyber.orders') }}" class="mt-3 sm:mt-4 inline-flex items-center text-xs font-bold text-[#00ffc8] hover:underline">
                    View All
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Food Pending Orders -->
        <div class="group card p-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl hover:border-[#ff7b54]/50 transition-all duration-300 hover:scale-105 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#ff7b54]/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#ff7b54] to-[#fee140] rounded-xl flex items-center justify-center" style="box-shadow: 0 0 20px rgba(255, 123, 84, 0.4);">
                        <svg class="w-7 h-7 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-[#ff7b54]/20 text-[#ff7b54] rounded-full">FOOD</span>
                </div>
                <p class="text-4xl font-black text-white mb-1 group-hover:text-[#ff7b54] transition-colors">{{ $foodPendingOrders }}</p>
                <p class="text-sm text-[#5c6b7f]">Pending Orders</p>
                <a href="{{ route('food.orders') }}" class="mt-4 inline-flex items-center text-xs font-bold text-[#ff7b54] hover:underline">
                    View All
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Cyber Total Spent -->
        <div class="group card p-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl hover:border-[#00ffc8]/50 transition-all duration-300 hover:scale-105 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#00ffc8]/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#8b5cf6] to-[#f5576c] rounded-xl flex items-center justify-center" style="box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-white mb-1 group-hover:text-[#00ffc8] transition-colors">TZS {{ number_format($cyberTotalSpent) }}</p>
                <p class="text-sm text-[#5c6b7f]">Cyber Total Spent</p>
            </div>
        </div>

        <!-- Food Total Spent -->
        <div class="group card p-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl hover:border-[#ff7b54]/50 transition-all duration-300 hover:scale-105 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#ff7b54]/10 rounded-full blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#ff7b54] to-[#fee140] rounded-xl flex items-center justify-center" style="box-shadow: 0 0 20px rgba(255, 123, 84, 0.4);">
                        <svg class="w-7 h-7 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-white mb-1 group-hover:text-[#ff7b54] transition-colors">TZS {{ number_format($foodTotalSpent) }}</p>
                <p class="text-sm text-[#5c6b7f]">Food Total Spent</p>
            </div>
        </div>
    </div>

    <!-- Active Subscription -->
    @if($activeSubscription)
    <div class="mb-12">
        <div class="card p-8 bg-gradient-to-br from-[#ff7b54]/10 via-[#fee140]/5 to-transparent border border-[#ff7b54]/30 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#ff7b54]/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <div class="flex items-center space-x-3 mb-3">
                            <h2 class="text-3xl font-black text-white">{{ $activeSubscription->package->name }}</h2>
                            <span class="px-4 py-2 bg-gradient-to-r from-[#00ffc8] to-[#00d9f5] text-[#0a0a0f] text-xs font-black rounded-full">ACTIVE</span>
                        </div>
                        <p class="text-lg text-[#a8b2c1]">{{ $activeSubscription->package->description }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-[#5c6b7f] mb-1">Package Price</p>
                        <p class="text-4xl font-black text-[#ff7b54]">TZS {{ number_format($activeSubscription->package->base_price) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-5 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-xs text-[#5c6b7f] mb-2">Start Date</p>
                        <p class="text-lg font-bold text-white">{{ $activeSubscription->start_date->format('M d, Y') }}</p>
                    </div>
                    <div class="p-5 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-xs text-[#5c6b7f] mb-2">End Date</p>
                        <p class="text-lg font-bold text-white">{{ $activeSubscription->end_date->format('M d, Y') }}</p>
                    </div>
                    <div class="p-5 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-xs text-[#5c6b7f] mb-2">Days Remaining</p>
                        <p class="text-2xl font-black text-[#ff7b54]">{{ $activeSubscription->getDaysRemaining() }}</p>
                    </div>
                </div>

                <a href="{{ route('food.subscription.show', $activeSubscription) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#ff7b54] to-[#fee140] text-[#0a0a0f] font-black rounded-xl hover:scale-105 transition-all" style="box-shadow: 0 0 30px rgba(255, 123, 84, 0.3);">
                    Manage Subscription
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <!-- Cyber Cafe Quick Action -->
        <a href="{{ route('cyber.menu') }}" class="group card p-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl hover:border-[#00ffc8]/50 transition-all duration-500 hover:scale-105 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-[#00ffc8]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#00ffc8] to-[#00d9f5] rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all" style="box-shadow: 0 0 30px rgba(0, 255, 200, 0.4);">
                        <svg class="w-8 h-8 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white group-hover:text-[#00ffc8] transition-colors">Order Cyber Cafe</h3>
                        <p class="text-sm text-[#5c6b7f]">Chakula kitamu kilichopikwa</p>
                    </div>
                </div>
                <div class="flex items-center text-[#00ffc8] font-bold">
                    <span>Agiza Sasa</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Monana Food Quick Action -->
        <a href="{{ route('food.packages') }}" class="group card p-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl hover:border-[#ff7b54]/50 transition-all duration-500 hover:scale-105 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-[#ff7b54]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#ff7b54] to-[#fee140] rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all" style="box-shadow: 0 0 30px rgba(255, 123, 84, 0.4);">
                        <svg class="w-8 h-8 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white group-hover:text-[#ff7b54] transition-colors">Browse Packages</h3>
                        <p class="text-sm text-[#5c6b7f]">Bidhaa za jikoni za ubora</p>
                    </div>
                </div>
                <div class="flex items-center text-[#ff7b54] font-bold">
                    <span>Angalia Packages</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-black text-white">Recent Activity</h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('cyber.orders') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-sm font-medium rounded-xl transition-colors">Cyber Orders</a>
                <a href="{{ route('food.orders') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-sm font-medium rounded-xl transition-colors">Food Orders</a>
            </div>
        </div>

        @if($recentActivity->count() > 0)
            <div class="card overflow-hidden bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl">
                <div class="divide-y divide-white/5">
                    @foreach($recentActivity as $activity)
                        <a href="{{ $activity['url'] }}" class="block p-6 hover:bg-white/5 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                        @if($activity['type'] === 'cyber_order') bg-[#00ffc8]/20
                                        @elseif($activity['type'] === 'food_order') bg-[#ff7b54]/20
                                        @else bg-[#8b5cf6]/20
                                        @endif
                                    ">
                                        @if($activity['type'] === 'cyber_order')
                                            <svg class="w-6 h-6 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        @elseif($activity['type'] === 'food_order')
                                            <svg class="w-6 h-6 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white">{{ $activity['title'] }}</h3>
                                        <p class="text-sm text-[#5c6b7f]">{{ $activity['description'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full mb-2 block
                                        @if($activity['status'] === 'pending') bg-yellow-500/20 text-yellow-400
                                        @elseif($activity['status'] === 'delivered' || $activity['status'] === 'active') bg-green-500/20 text-green-400
                                        @elseif($activity['status'] === 'cancelled') bg-red-500/20 text-red-400
                                        @else bg-gray-500/20 text-gray-400
                                        @endif
                                    ">{{ strtoupper($activity['status']) }}</span>
                                    <p class="text-sm font-bold
                                        @if($activity['type'] === 'cyber_order') text-[#00ffc8]
                                        @elseif($activity['type'] === 'food_order') text-[#ff7b54]
                                        @else text-[#8b5cf6]
                                        @endif
                                    ">TZS {{ number_format($activity['amount']) }}</p>
                                    <p class="text-xs text-[#5c6b7f] mt-1">{{ $activity['date']->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card p-12 text-center bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl">
                <div class="w-20 h-20 bg-gradient-to-br from-[#8b5cf6] to-[#f5576c] rounded-3xl flex items-center justify-center mx-auto mb-6" style="box-shadow: 0 0 30px rgba(139, 92, 246, 0.4);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No Activity Yet</h3>
                <p class="text-[#5c6b7f] mb-6">Start ordering to see your activity here!</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('cyber.menu') }}" class="px-6 py-3 bg-gradient-to-r from-[#00ffc8] to-[#00d9f5] text-[#0a0a0f] font-bold rounded-xl hover:scale-105 transition-all">Order Cyber Cafe</a>
                    <a href="{{ route('food.packages') }}" class="px-6 py-3 bg-gradient-to-r from-[#ff7b54] to-[#fee140] text-[#0a0a0f] font-bold rounded-xl hover:scale-105 transition-all">Browse Packages</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
