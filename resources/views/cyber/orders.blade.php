@extends('layouts.app')

@section('title', 'My Orders - Cyber Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Cyber Cafe Orders</h1>
        <p class="text-[#a0a0a0]">View your order history and track deliveries</p>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card p-6 hover:border-[#00d4aa]/50 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-bold text-white">{{ $order->order_number }}</h3>
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    @if($order->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @elseif($order->status === 'preparing') bg-blue-500/20 text-blue-400
                                    @elseif($order->status === 'ready') bg-purple-500/20 text-purple-400
                                    @elseif($order->status === 'delivered') bg-green-500/20 text-green-400
                                    @elseif($order->status === 'cancelled') bg-red-500/20 text-red-400
                                    @else bg-gray-500/20 text-gray-400
                                    @endif
                                ">{{ strtoupper($order->status) }}</span>
                            </div>
                            <div class="space-y-1 text-sm">
                                <div class="flex items-center text-[#6b6b6b]">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $order->mealSlot->display_name ?? 'N/A' }} - {{ $order->mealSlot->delivery_time_label ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center text-[#6b6b6b]">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ Str::limit($order->delivery_address, 50) }}</span>
                                </div>
                                <div class="flex items-center text-[#6b6b6b]">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-2xl font-bold text-[#00d4aa] mb-2">TZS {{ number_format($order->total_amount) }}</p>
                            <a href="{{ route('cyber.order.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-[#00d4aa]/20 hover:bg-[#00d4aa]/30 text-[#00d4aa] text-sm font-medium rounded-lg transition-colors">
                                View Details
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <span class="text-xs text-white">{{ $item->item_name }}</span>
                                    <span class="text-xs text-[#6b6b6b] ml-1">x{{ $item->quantity }}</span>
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
        <div class="card p-12 text-center">
            <div class="w-20 h-20 bg-[#00d4aa]/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Orders Yet</h3>
            <p class="text-[#6b6b6b] mb-6">You haven't placed any orders yet. Start ordering from our delicious menu!</p>
            <a href="{{ route('cyber.menu') }}" class="inline-flex items-center px-6 py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Browse Menu
            </a>
        </div>
    @endif
</div>
@endsection
