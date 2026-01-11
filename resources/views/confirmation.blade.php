@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($order) && $order->payments->first()?->status === 'paid')
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-8">
            <h1 class="text-2xl font-bold mb-2">✅ Order Confirmed!</h1>
            <p>Thank you for your order. Your payment has been received successfully.</p>
        </div>
    @elseif(isset($subscription) && $subscription->payments->first()?->status === 'paid')
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-8">
            <h1 class="text-2xl font-bold mb-2">✅ Subscription Confirmed!</h1>
            <p>Thank you for your subscription. Your payment has been received successfully.</p>
        </div>
    @else
        <div class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg mb-8">
            <h1 class="text-2xl font-bold mb-2">⏳ Payment Pending</h1>
            <p>Please complete the payment on your mobile phone. We are checking your payment status and will update automatically.</p>
        </div>
    @endif

    @if(isset($order))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Order Details</h2>
            <div class="space-y-2 mb-4">
                <p class="text-slate-600 dark:text-slate-400"><strong>Order ID:</strong> <span class="font-mono text-blue-700 dark:text-blue-300">{{ $order->order_number }}</span></p>
                <p class="text-slate-600 dark:text-slate-400"><strong>Status:</strong> <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                    @if($order->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                    @endif
                ">{{ ucfirst($order->status) }}</span></p>
                <p class="text-slate-600 dark:text-slate-400"><strong>Total Amount:</strong> <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">TZS {{ number_format($order->total_amount, 2) }}</span></p>
                <p class="text-slate-600 dark:text-slate-400"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                @if($order->notes)
                    <p class="text-slate-600 dark:text-slate-400"><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Order Items:</h3>
                @foreach($order->orderItems as $item)
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $item->orderable->name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Qty: {{ $item->quantity }} × TZS {{ number_format($item->price, 2) }}</p>
                            @if($item->notes)
                                <p class="text-xs text-slate-500 dark:text-slate-500 italic">Note: {{ $item->notes }}</p>
                            @endif
                        </div>
                        <p class="font-semibold text-blue-700 dark:text-blue-300">TZS {{ number_format($item->quantity * $item->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($subscription))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Subscription Details</h2>
            <div class="space-y-2 mb-4">
                <p class="text-slate-600 dark:text-slate-400"><strong>Package:</strong> {{ $subscription->subscriptionPackage->name }}</p>
                <p class="text-slate-600 dark:text-slate-400"><strong>Start Date:</strong> {{ $subscription->start_date->format('F d, Y') }}</p>
                <p class="text-slate-600 dark:text-slate-400"><strong>End Date:</strong> {{ $subscription->end_date->format('F d, Y') }}</p>
                <p class="text-slate-600 dark:text-slate-400"><strong>Status:</strong> <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                    @if($subscription->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($subscription->status === 'paused') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @endif
                ">{{ ucfirst($subscription->status) }}</span></p>
            </div>

            @if($subscription->delivery_schedule)
                <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Delivery Schedule:</h3>
                    <ul class="list-disc list-inside space-y-1 text-slate-600 dark:text-slate-400">
                        @foreach($subscription->delivery_schedule as $date)
                            <li>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="flex justify-center space-x-4">
        <a href="{{ route('home') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
