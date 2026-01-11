@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Header -->
<div class="mb-8 animate-slide-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent mb-2">Dashboard Overview</h1>
            <p class="text-slate-600 dark:text-slate-400">Welcome back! Here's what's happening with your business today.</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 hover:from-orange-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-2xl flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>View All Orders</span>
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Pending Orders Card -->
    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-2xl shadow-xl p-6 border border-yellow-200 dark:border-yellow-800/50 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-in">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 text-xs font-bold rounded-full">Pending</span>
        </div>
        <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">Pending Orders</h3>
        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $pendingOrders }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            Requires attention
        </p>
    </div>

    <!-- Total Orders Card -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl shadow-xl p-6 border border-blue-200 dark:border-blue-800/50 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-bold rounded-full">All Time</span>
        </div>
        <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">Total Orders</h3>
        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $totalOrders }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
            </svg>
            Total orders received
        </p>
    </div>

    <!-- Active Subscriptions Card -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl shadow-xl p-6 border border-green-200 dark:border-green-800/50 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-bold rounded-full">Active</span>
        </div>
        <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">Active Subscriptions</h3>
        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $activeSubscriptions }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Currently active
        </p>
    </div>

    <!-- Pending Payments Card -->
    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl shadow-xl p-6 border border-purple-200 dark:border-purple-800/50 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slide-in" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300 text-xs font-bold rounded-full">Pending</span>
        </div>
        <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">Pending Payments</h3>
        <p class="text-4xl font-bold text-slate-900 dark:text-white mb-2">{{ $pendingPayments }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            Awaiting payment
        </p>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-slide-in">
        <div class="bg-gradient-to-r from-orange-500 via-pink-500 via-purple-500 to-blue-500 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
            <div class="flex items-center justify-between relative z-10">
                <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Recent Orders</span>
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-white/90 hover:text-white text-sm font-semibold transition-colors flex items-center space-x-1 backdrop-blur-sm">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($orders as $order)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all border border-slate-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                                {{ substr($order->order_number, -3) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $order->order_number }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 truncate">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">TZS {{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                @if($order->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @endif
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                            <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 font-medium">No recent orders</p>
                        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Orders will appear here as they come in</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-slide-in" style="animation-delay: 0.2s">
        <div class="bg-gradient-to-r from-cyan-400 via-blue-500 via-indigo-500 to-purple-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
            <div class="flex items-center justify-between relative z-10">
                <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Recent Subscriptions</span>
                </h2>
                <a href="{{ route('admin.subscriptions.index') }}" class="text-white/90 hover:text-white text-sm font-semibold transition-colors flex items-center space-x-1 backdrop-blur-sm">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($subscriptions as $subscription)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all border border-slate-200 dark:border-slate-600">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                                📦
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $subscription->subscriptionPackage->name }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 truncate">{{ $subscription->user->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                    {{ $subscription->start_date->format('M d') }} - {{ $subscription->end_date->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                @if($subscription->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($subscription->status === 'paused') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @endif
                            ">
                                {{ ucfirst($subscription->status) }}
                            </span>
                            <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 font-medium">No recent subscriptions</p>
                        <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Subscriptions will appear here as they are created</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer {
        animation: shimmer 3s infinite;
    }
</style>
@endsection
