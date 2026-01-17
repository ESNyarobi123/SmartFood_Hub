@extends('admin.layout')

@section('title', 'Monana Market Dashboard')
@section('subtitle', 'Manage kitchen products and subscriptions')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-[#ff6b35]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Active Subscriptions</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#ff6b35]/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Pending Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Products</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-[#6b6b6b] mt-2">{{ $stats['available_products'] }} available</p>
        </div>

        <div class="card p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-white mt-1">TZS {{ number_format($stats['subscription_revenue']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions by Package -->
    <div class="card overflow-hidden">
        <div class="p-4 border-b border-[#333]">
            <h3 class="font-bold text-white">Subscriptions by Package</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#333]">
            @foreach($subscriptionsByPackage as $package)
                <div class="p-5">
                    <h4 class="text-lg font-bold text-white mb-2">{{ $package->name }}</h4>
                    <p class="text-sm text-[#6b6b6b] mb-4">TZS {{ number_format($package->base_price) }}/{{ $package->duration_type }}</p>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-[#ff6b35]">{{ $package->active_count }}</p>
                            <p class="text-xs text-[#6b6b6b]">Active</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $package->total_count }}</p>
                            <p class="text-xs text-[#6b6b6b]">Total</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions & Low Stock Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="card p-5">
            <h3 class="font-bold text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.food.products.create') }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-[#ff6b35]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">Add Product</span>
                    </div>
                    <svg class="w-4 h-4 text-[#6b6b6b] group-hover:text-[#ff6b35] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.food.packages.create') }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">Create Package</span>
                    </div>
                    <svg class="w-4 h-4 text-[#6b6b6b] group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.food.subscriptions.index', ['status' => 'pending']) }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">Pending Subscriptions</span>
                    </div>
                    @if($stats['pending_subscriptions'] > 0)
                        <span class="px-2 py-0.5 text-xs font-bold bg-yellow-500 text-black rounded-full">{{ $stats['pending_subscriptions'] }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="lg:col-span-2 card overflow-hidden">
            <div class="p-4 border-b border-[#333] bg-red-500/10">
                <h3 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Low Stock Alert
                </h3>
            </div>
            @if($lowStockProducts->isNotEmpty())
                <div class="divide-y divide-[#333]">
                    @foreach($lowStockProducts as $product)
                        <div class="p-4 flex items-center justify-between hover:bg-[#2d2d2d] transition-colors">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $product->name }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $product->unit }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-red-500">{{ $product->stock_quantity }}</span>
                                <span class="text-xs text-[#6b6b6b]"> in stock</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-[#6b6b6b]">
                    <p>All products are well stocked</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Subscriptions -->
        <div class="card overflow-hidden">
            <div class="p-4 border-b border-[#333]">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-white">Recent Subscriptions</h3>
                    <a href="{{ route('admin.food.subscriptions.index') }}" class="text-sm text-[#ff6b35] hover:underline">View All</a>
                </div>
            </div>
            <div class="divide-y divide-[#333]">
                @forelse($recentSubscriptions as $subscription)
                    <div class="p-4 hover:bg-[#2d2d2d] transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $subscription->package->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $subscription->user->name ?? 'Guest' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($subscription->status === 'active') bg-green-500/20 text-green-500
                                    @elseif($subscription->status === 'paused') bg-yellow-500/20 text-yellow-500
                                    @elseif($subscription->status === 'pending') bg-blue-500/20 text-blue-500
                                    @else bg-gray-500/20 text-gray-500
                                    @endif
                                ">{{ ucfirst($subscription->status) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[#6b6b6b]">
                        <p>No subscriptions yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card overflow-hidden">
            <div class="p-4 border-b border-[#333]">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-white">Recent Orders</h3>
                    <a href="{{ route('admin.food.orders.index') }}" class="text-sm text-[#ff6b35] hover:underline">View All</a>
                </div>
            </div>
            <div class="divide-y divide-[#333]">
                @forelse($recentOrders as $order)
                    <div class="p-4 hover:bg-[#2d2d2d] transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $order->order_number }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $order->user->name ?? 'Guest' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($order->status === 'pending') bg-yellow-500/20 text-yellow-500
                                    @elseif($order->status === 'delivered') bg-green-500/20 text-green-500
                                    @else bg-blue-500/20 text-blue-500
                                    @endif
                                ">{{ ucfirst($order->status) }}</span>
                                <p class="text-xs text-[#6b6b6b] mt-1">TZS {{ number_format($order->total_amount) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[#6b6b6b]">
                        <p>No orders yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
