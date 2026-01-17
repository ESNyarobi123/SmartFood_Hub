@extends('admin.layout')

@section('title', 'Overview')
@section('subtitle', 'Monana Platform Statistics')

@section('content')
<div class="space-y-8">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Cyber Orders -->
        <div class="card stat-card cyber p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0, 255, 200, 0.2), rgba(0, 217, 245, 0.1));">
                    <svg class="w-6 h-6 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold px-3 py-1.5 rounded-full" style="background: linear-gradient(135deg, #00ffc8, #00d9f5); color: #0a0a0f;">CYBER</span>
            </div>
            <p class="text-3xl font-bold text-white mb-1 group-hover:text-[#00ffc8] transition-colors">{{ $cyberStats['pending_orders'] }}</p>
            <p class="text-sm text-[#5c6b7f]">Pending Orders</p>
            <div class="mt-4 pt-4 border-t border-white/5">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-[#a8b2c1]">Today's orders</p>
                    <p class="text-sm font-semibold text-[#00ffc8]">{{ $cyberStats['today_orders'] }}</p>
                </div>
            </div>
        </div>

        <!-- Food Subscriptions -->
        <div class="card stat-card food p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255, 123, 84, 0.2), rgba(254, 225, 64, 0.1));">
                    <svg class="w-6 h-6 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold px-3 py-1.5 rounded-full" style="background: linear-gradient(135deg, #ff7b54, #fee140); color: #0a0a0f;">FOOD</span>
            </div>
            <p class="text-3xl font-bold text-white mb-1 group-hover:text-[#ff7b54] transition-colors">{{ $foodStats['active_subscriptions'] }}</p>
            <p class="text-sm text-[#5c6b7f]">Active Subscriptions</p>
            <div class="mt-4 pt-4 border-t border-white/5">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-[#a8b2c1]">Pending orders</p>
                    <p class="text-sm font-semibold text-[#ff7b54]">{{ $foodStats['pending_orders'] }}</p>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <div class="card stat-card purple p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(245, 87, 108, 0.1));">
                    <svg class="w-6 h-6 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold px-3 py-1.5 rounded-full" style="background: linear-gradient(135deg, #8b5cf6, #f5576c); color: white;">PAYMENTS</span>
            </div>
            <p class="text-3xl font-bold text-white mb-1 group-hover:text-[#8b5cf6] transition-colors">{{ $paymentStats['pending'] }}</p>
            <p class="text-sm text-[#5c6b7f]">Pending Payments</p>
            <div class="mt-4 pt-4 border-t border-white/5">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-[#a8b2c1]">Today received</p>
                    <p class="text-sm font-semibold text-[#8b5cf6]">TZS {{ number_format($paymentStats['today_paid']) }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="card p-6 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 opacity-10" style="background: radial-gradient(circle, #10b981 0%, transparent 70%);"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.1));">
                    <svg class="w-6 h-6 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex items-center space-x-1.5">
                    <div class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></div>
                    <span class="text-[10px] font-semibold text-[#10b981]">LIVE</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-white mb-1 group-hover:text-[#10b981] transition-colors">TZS {{ number_format($cyberStats['today_revenue']) }}</p>
            <p class="text-sm text-[#5c6b7f]">Today's Revenue</p>
            <div class="mt-4 pt-4 border-t border-white/5">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-[#10b981] mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    <span class="text-xs text-[#10b981]">Monana Food earnings</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Monana Food Card -->
        <a href="{{ route('admin.cyber.dashboard') }}" class="card p-6 group relative overflow-hidden">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="background: radial-gradient(circle at top right, rgba(0, 255, 200, 0.1) 0%, transparent 60%);"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(135deg, #00ffc8, #00d9f5);">
                        <svg class="w-8 h-8 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-[#00ffc8] transition-colors">Monana Food</h3>
                        <p class="text-sm text-[#5c6b7f]">Cooked food delivery with meal slots</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 rounded-xl" style="background: rgba(0, 255, 200, 0.05); border: 1px solid rgba(0, 255, 200, 0.1);">
                        <p class="text-2xl font-bold text-[#00ffc8]">{{ $cyberStats['pending_orders'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Pending</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-white/5 border border-white/5">
                        <p class="text-2xl font-bold text-white">{{ $cyberStats['today_orders'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Today</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-white/5 border border-white/5">
                        <p class="text-2xl font-bold text-white">{{ $cyberStats['total_orders'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Total</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                <svg class="w-6 h-6 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </div>
        </a>

        <!-- Monana Market Card -->
        <a href="{{ route('admin.food.dashboard') }}" class="card p-6 group relative overflow-hidden">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="background: radial-gradient(circle at top right, rgba(255, 123, 84, 0.1) 0%, transparent 60%);"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(135deg, #ff7b54, #fee140);">
                        <svg class="w-8 h-8 text-[#0a0a0f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-[#ff7b54] transition-colors">Monana Market</h3>
                        <p class="text-sm text-[#5c6b7f]">Kitchen essentials & subscriptions</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 rounded-xl" style="background: rgba(255, 123, 84, 0.05); border: 1px solid rgba(255, 123, 84, 0.1);">
                        <p class="text-2xl font-bold text-[#ff7b54]">{{ $foodStats['active_subscriptions'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Active</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-white/5 border border-white/5">
                        <p class="text-2xl font-bold text-white">{{ $foodStats['pending_orders'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Pending</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-white/5 border border-white/5">
                        <p class="text-2xl font-bold text-white">{{ $foodStats['total_subscriptions'] }}</p>
                        <p class="text-xs text-[#5c6b7f] mt-1">Total</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                <svg class="w-6 h-6 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Cyber Orders -->
        <div class="card overflow-hidden">
            <div class="p-5 border-b border-white/5 relative">
                <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(0, 255, 200, 0.08) 0%, transparent 100%);"></div>
                <div class="relative flex items-center justify-between">
                    <h3 class="font-bold text-white flex items-center">
                        <span class="w-2 h-2 rounded-full mr-2.5" style="background: linear-gradient(135deg, #00ffc8, #00d9f5);"></span>
                        Recent Cyber Orders
                    </h3>
                    <a href="{{ route('admin.cyber.orders.index') }}" class="text-xs font-medium text-[#00ffc8] hover:underline flex items-center">
                        View All
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($recentCyberOrders as $order)
                    <div class="p-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0, 255, 200, 0.1), rgba(0, 217, 245, 0.05));">
                                    <span class="text-xs font-bold text-[#00ffc8]">#{{ substr($order->order_number, -3) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $order->order_number }}</p>
                                    <p class="text-xs text-[#5c6b7f]">{{ $order->user->name ?? 'Guest' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full
                                    @if($order->status === 'pending') bg-amber-500/20 text-amber-400
                                    @elseif($order->status === 'delivered') bg-emerald-500/20 text-emerald-400
                                    @elseif($order->status === 'preparing') bg-blue-500/20 text-blue-400
                                    @else bg-gray-500/20 text-gray-400
                                    @endif
                                ">{{ strtoupper($order->status) }}</span>
                                <p class="text-xs text-[#a8b2c1] mt-1.5 font-medium">TZS {{ number_format($order->total_amount) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0, 255, 200, 0.1), rgba(0, 217, 245, 0.05));">
                            <svg class="w-8 h-8 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-[#5c6b7f] text-sm">No orders yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Food Subscriptions -->
        <div class="card overflow-hidden">
            <div class="p-5 border-b border-white/5 relative">
                <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(255, 123, 84, 0.08) 0%, transparent 100%);"></div>
                <div class="relative flex items-center justify-between">
                    <h3 class="font-bold text-white flex items-center">
                        <span class="w-2 h-2 rounded-full mr-2.5" style="background: linear-gradient(135deg, #ff7b54, #fee140);"></span>
                        Recent Subscriptions
                    </h3>
                    <a href="{{ route('admin.food.subscriptions.index') }}" class="text-xs font-medium text-[#ff7b54] hover:underline flex items-center">
                        View All
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($recentSubscriptions as $subscription)
                    <div class="p-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255, 123, 84, 0.1), rgba(254, 225, 64, 0.05));">
                                    <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $subscription->package->name ?? 'Unknown Package' }}</p>
                                    <p class="text-xs text-[#5c6b7f]">{{ $subscription->user->name ?? 'Guest' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full
                                    @if($subscription->status === 'active') bg-emerald-500/20 text-emerald-400
                                    @elseif($subscription->status === 'paused') bg-amber-500/20 text-amber-400
                                    @elseif($subscription->status === 'pending') bg-blue-500/20 text-blue-400
                                    @else bg-gray-500/20 text-gray-400
                                    @endif
                                ">{{ strtoupper($subscription->status) }}</span>
                                <p class="text-xs text-[#a8b2c1] mt-1.5">{{ $subscription->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255, 123, 84, 0.1), rgba(254, 225, 64, 0.05));">
                            <svg class="w-8 h-8 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <p class="text-[#5c6b7f] text-sm">No subscriptions yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
