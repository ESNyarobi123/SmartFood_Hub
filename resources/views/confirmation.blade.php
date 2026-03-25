@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="text-[#9ca3af] hover:text-white transition-colors">Home</a>
        <svg class="w-4 h-4 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-white font-medium">Confirmation</span>
    </nav>

    @if(isset($order) && $order->payments->first()?->status === 'paid')
        <div class="card p-4 sm:p-6 mb-6 border-green-500/30 bg-green-500/5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-green-400 mb-1">Order Confirmed!</h1>
                    <p class="text-sm text-green-300/80">Thank you for your order. Your payment has been received successfully.</p>
                </div>
            </div>
        </div>
    @elseif(isset($subscription) && $subscription->payments->first()?->status === 'paid')
        <div class="card p-4 sm:p-6 mb-6 border-green-500/30 bg-green-500/5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-green-400 mb-1">Subscription Confirmed!</h1>
                    <p class="text-sm text-green-300/80">Thank you for your subscription. Your payment has been received successfully.</p>
                </div>
            </div>
        </div>
    @else
        <div class="card p-4 sm:p-6 mb-6 border-yellow-500/30 bg-yellow-500/5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-yellow-400 mb-1">Payment Pending</h1>
                    <p class="text-sm text-yellow-300/80">Please complete the payment on your mobile phone. We are checking your payment status and will update automatically.</p>
                </div>
            </div>
        </div>
    @endif

    @if(isset($order))
        <div class="card p-4 sm:p-6 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-white mb-4">Order Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Order ID</p>
                    <p class="text-sm font-mono font-medium text-white">{{ $order->order_number }}</p>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Status</p>
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                        @if($order->status === 'approved') bg-green-500/20 text-green-400
                        @elseif($order->status === 'pending') bg-yellow-500/20 text-yellow-400
                        @else bg-blue-500/20 text-blue-400
                        @endif
                    ">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Total Amount</p>
                    <p class="text-lg font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Delivery Address</p>
                    <p class="text-sm text-white">{{ $order->delivery_address }}</p>
                </div>
                @if($order->notes)
                    <div class="p-3 bg-[#1a1a1a] rounded-lg sm:col-span-2">
                        <p class="text-xs text-[#6b6b6b] mb-1">Notes</p>
                        <p class="text-sm text-white">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="border-t border-[#333] pt-4">
                <h3 class="text-sm font-semibold text-[#9ca3af] mb-3">Order Items:</h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#333] last:border-0 gap-1">
                            <div>
                                <p class="font-medium text-white text-sm">{{ $item->orderable->name }}</p>
                                <p class="text-xs text-[#6b6b6b]">Qty: {{ $item->quantity }} × TZS {{ number_format($item->price, 2) }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-[#9ca3af] italic mt-0.5">Note: {{ $item->notes }}</p>
                                @endif
                            </div>
                            <p class="font-semibold text-[#ff6b35] text-sm">TZS {{ number_format($item->quantity * $item->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(isset($subscription))
        <div class="card p-4 sm:p-6 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-white mb-4">Subscription Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Package</p>
                    <p class="text-sm font-medium text-white">{{ $subscription->subscriptionPackage->name }}</p>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Status</p>
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                        @if($subscription->status === 'active') bg-green-500/20 text-green-400
                        @elseif($subscription->status === 'paused') bg-yellow-500/20 text-yellow-400
                        @else bg-red-500/20 text-red-400
                        @endif
                    ">{{ ucfirst($subscription->status) }}</span>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">Start Date</p>
                    <p class="text-sm font-medium text-white">{{ $subscription->start_date->format('F d, Y') }}</p>
                </div>
                <div class="p-3 bg-[#1a1a1a] rounded-lg">
                    <p class="text-xs text-[#6b6b6b] mb-1">End Date</p>
                    <p class="text-sm font-medium text-white">{{ $subscription->end_date->format('F d, Y') }}</p>
                </div>
            </div>

            @if($subscription->delivery_schedule)
                <div class="border-t border-[#333] pt-4">
                    <h3 class="text-sm font-semibold text-[#9ca3af] mb-3">Delivery Schedule:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($subscription->delivery_schedule as $date)
                            <div class="flex items-center gap-2 p-2 bg-[#1a1a1a] rounded-lg">
                                <svg class="w-4 h-4 text-[#ff6b35] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm text-white">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-center gap-3">
        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white rounded-lg font-medium text-sm text-center transition-colors">
            My Dashboard
        </a>
        <a href="{{ route('home') }}" class="px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white rounded-lg font-bold text-sm text-center transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
