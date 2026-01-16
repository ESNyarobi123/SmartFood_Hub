@extends('layouts.app')

@section('title', 'My Subscriptions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Subscriptions</h1>
        <p class="text-[#a0a0a0]">Manage your subscription packages and upcoming deliveries</p>
    </div>

    @if($activeSubscription)
        <!-- Active Subscription Card -->
        <div class="card p-6 mb-8 glow-food">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h2 class="text-2xl font-bold text-white">{{ $activeSubscription->package->name }}</h2>
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded-full">Active</span>
                    </div>
                    <p class="text-[#a0a0a0]">{{ $activeSubscription->package->description }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-[#6b6b6b] mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-[#ff6b35]">TZS {{ number_format($activeSubscription->package->base_price) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-[#2d2d2d] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Start Date</p>
                    <p class="text-sm font-medium text-white">{{ $activeSubscription->start_date->format('M d, Y') }}</p>
                </div>
                <div class="p-4 bg-[#2d2d2d] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">End Date</p>
                    <p class="text-sm font-medium text-white">{{ $activeSubscription->end_date->format('M d, Y') }}</p>
                </div>
                <div class="p-4 bg-[#2d2d2d] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Days Remaining</p>
                    <p class="text-sm font-medium text-[#ff6b35]">{{ $activeSubscription->getDaysRemaining() }} days</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('food.subscription.show', $activeSubscription) }}" class="px-4 py-2 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
                    View Details
                </a>
                @if($activeSubscription->canBePaused())
                    <form action="{{ route('food.subscription.pause', $activeSubscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to pause this subscription?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 font-medium rounded-lg transition-colors">
                            Pause Subscription
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Upcoming Deliveries -->
        @if(count($upcomingDeliveries) > 0)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-white mb-4">Upcoming Deliveries</h2>
                <div class="space-y-4">
                    @foreach($upcomingDeliveries as $delivery)
                        <div class="card p-5 {{ $delivery['is_paused'] ? 'opacity-60' : '' }}">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-white">{{ $delivery['date']->format('l, M d, Y') }}</h3>
                                        @if($delivery['is_paused'])
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-bold rounded-full">Paused</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-[#6b6b6b]">{{ $delivery['date']->diffForHumans() }}</p>
                                </div>
                                @if($delivery['can_customize'] && !$delivery['is_paused'])
                                    <a href="{{ route('food.subscription.show', $activeSubscription) }}?date={{ $delivery['date']->format('Y-m-d') }}" class="px-3 py-1.5 bg-[#ff6b35]/20 hover:bg-[#ff6b35]/30 text-[#ff6b35] text-sm font-medium rounded-lg transition-colors">
                                        Customize
                                    </a>
                                @endif
                            </div>

                            <!-- Delivery Items -->
                            <div class="space-y-2">
                                @foreach($delivery['items'] as $item)
                                    <div class="flex items-center justify-between p-3 bg-[#2d2d2d] rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-[#ff6b35]/20 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-white">{{ $item['product']->name }}</p>
                                                <p class="text-xs text-[#6b6b6b]">{{ $item['quantity'] }} {{ $item['product']->unit }}</p>
                                            </div>
                                        </div>
                                        @if(isset($item['is_modified']) && $item['is_modified'])
                                            <span class="text-xs text-blue-400">{{ $item['modification'] ?? 'Modified' }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <!-- No Active Subscription -->
        <div class="card p-12 text-center">
            <div class="w-20 h-20 bg-[#ff6b35]/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Active Subscription</h3>
            <p class="text-[#6b6b6b] mb-6">You don't have an active subscription yet. Browse our packages to get started!</p>
            <a href="{{ route('food.packages') }}" class="inline-flex items-center px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Browse Packages
            </a>
        </div>
    @endif

    <!-- Subscription History -->
    @if($subscriptions->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-white mb-4">Subscription History</h2>
            <div class="card overflow-hidden">
                <div class="divide-y divide-[#333]">
                    @foreach($subscriptions as $subscription)
                        <div class="p-5 hover:bg-[#2d2d2d] transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-[#ff6b35]/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white">{{ $subscription->package->name }}</h3>
                                        <p class="text-sm text-[#6b6b6b]">
                                            {{ $subscription->start_date->format('M d, Y') }} - {{ $subscription->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($subscription->status === 'active') bg-green-500/20 text-green-400
                                        @elseif($subscription->status === 'paused') bg-yellow-500/20 text-yellow-400
                                        @elseif($subscription->status === 'cancelled') bg-red-500/20 text-red-400
                                        @else bg-gray-500/20 text-gray-400
                                        @endif
                                    ">{{ ucfirst($subscription->status) }}</span>
                                    <a href="{{ route('food.subscription.show', $subscription) }}" class="px-4 py-2 bg-[#333] hover:bg-[#444] text-white text-sm font-medium rounded-lg transition-colors">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
