@extends('layouts.app')

@section('title', 'My Orders - Monana Market')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm mb-4 sm:mb-6" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="text-[#9ca3af] hover:text-white transition-colors">Dashboard</a>
        <svg class="w-4 h-4 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-white font-medium">Market Orders</span>
    </nav>
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">My Food Orders</h1>
        <p class="text-sm sm:text-base text-[#9ca3af]">View your custom order history and track deliveries</p>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card p-4 sm:p-6 hover:border-[#ff6b35]/50 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4 mb-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="text-base sm:text-lg font-bold text-white">{{ $order->order_number }}</h3>
                                <span class="px-2 sm:px-3 py-0.5 sm:py-1 text-[10px] sm:text-xs font-bold rounded-full
                                    @if($order->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @elseif($order->status === 'preparing') bg-blue-500/20 text-blue-400
                                    @elseif($order->status === 'ready') bg-purple-500/20 text-purple-400
                                    @elseif($order->status === 'delivered') bg-green-500/20 text-green-400
                                    @elseif($order->status === 'cancelled') bg-red-500/20 text-red-400
                                    @else bg-gray-500/20 text-gray-400
                                    @endif
                                ">{{ strtoupper($order->status) }}</span>
                            </div>
                            <div class="space-y-1 text-xs sm:text-sm">
                                <div class="flex items-center text-[#9ca3af]">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ Str::limit($order->delivery_address, 50) }}</span>
                                </div>
                                <div class="flex items-center text-[#9ca3af]">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center sm:flex-col sm:items-end gap-3 sm:gap-2 sm:ml-4 flex-shrink-0">
                            <p class="text-lg sm:text-2xl font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount) }}</p>
                            <a href="{{ route('food.order.show', $order) }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-[#ff6b35]/20 hover:bg-[#ff6b35]/30 text-[#ff6b35] text-xs sm:text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                View Details
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-1.5 sm:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="mt-4 pt-4 border-t border-[#333]">
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                                <div class="px-3 py-1.5 bg-[#2d2d2d] rounded-lg">
                                    <span class="text-xs text-white">{{ $item->product_name }}</span>
                                    <span class="text-xs text-[#6b6b6b] ml-1">{{ $item->quantity }} {{ $item->unit }}</span>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="px-3 py-1.5 bg-[#2d2d2d] rounded-lg">
                                    <span class="text-xs text-[#6b6b6b]">+{{ $order->items->count() - 3 }} more</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card p-6 sm:p-8 lg:p-12 text-center">
            <div class="w-20 h-20 bg-[#ff6b35]/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Orders Yet</h3>
            <p class="text-[#6b6b6b] mb-6">You haven't placed any custom orders yet. Start shopping for kitchen essentials!</p>
            <a href="{{ route('food.custom') }}" class="inline-flex items-center px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
