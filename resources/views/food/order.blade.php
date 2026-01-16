@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('food.orders') }}" class="inline-flex items-center text-[#ff6b35] hover:text-[#e55a2b] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="card p-6 mb-6 glow-food">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">{{ $order->order_number }}</h1>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 text-sm font-bold rounded-full
                        @if($order->status === 'pending') bg-yellow-500/20 text-yellow-400
                        @elseif($order->status === 'preparing') bg-blue-500/20 text-blue-400
                        @elseif($order->status === 'ready') bg-purple-500/20 text-purple-400
                        @elseif($order->status === 'delivered') bg-green-500/20 text-green-400
                        @elseif($order->status === 'cancelled') bg-red-500/20 text-red-400
                        @else bg-gray-500/20 text-gray-400
                        @endif
                    ">{{ strtoupper($order->status) }}</span>
                    <span class="text-sm text-[#6b6b6b]">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-[#6b6b6b] mb-1">Total Amount</p>
                <p class="text-3xl font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount) }}</p>
            </div>
        </div>

        <!-- Order Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-[#2d2d2d] rounded-lg md:col-span-2">
                <p class="text-xs text-[#6b6b6b] mb-1">Delivery Address</p>
                <p class="text-sm font-medium text-white">{{ $order->delivery_address }}</p>
            </div>
            @if($order->notes)
                <div class="p-4 bg-[#2d2d2d] rounded-lg md:col-span-2">
                    <p class="text-xs text-[#6b6b6b] mb-1">Notes</p>
                    <p class="text-sm text-white">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="card p-6 mb-6">
        <h2 class="text-xl font-bold text-white mb-4">Order Items</h2>
        <div class="space-y-3">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between p-4 bg-[#2d2d2d] rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-[#ff6b35]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ $item->product_name }}</h3>
                            <p class="text-sm text-[#6b6b6b]">Quantity: {{ $item->quantity }} {{ $item->unit }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-[#6b6b6b]">TZS {{ number_format($item->unit_price) }}/{{ $item->unit }}</p>
                        <p class="text-lg font-bold text-[#ff6b35]">TZS {{ number_format($item->total_price) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="mt-6 pt-6 border-t border-[#333]">
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-white">Total</span>
                <span class="text-2xl font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    @if($order->payments->count() > 0)
        <div class="card p-6">
            <h2 class="text-xl font-bold text-white mb-4">Payment Information</h2>
            <div class="space-y-3">
                @foreach($order->payments as $payment)
                    <div class="flex items-center justify-between p-4 bg-[#2d2d2d] rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-white">Payment #{{ $payment->id }}</p>
                            <p class="text-xs text-[#6b6b6b]">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                @if($payment->status === 'completed') bg-green-500/20 text-green-400
                                @elseif($payment->status === 'pending') bg-yellow-500/20 text-yellow-400
                                @elseif($payment->status === 'failed') bg-red-500/20 text-red-400
                                @else bg-gray-500/20 text-gray-400
                                @endif
                            ">{{ strtoupper($payment->status) }}</span>
                            <p class="text-sm text-white mt-1">TZS {{ number_format($payment->amount) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card p-6 bg-yellow-500/10 border border-yellow-500/30">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-yellow-400">Payment Pending</p>
                    <p class="text-xs text-yellow-300/80">This order is waiting for payment confirmation.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
