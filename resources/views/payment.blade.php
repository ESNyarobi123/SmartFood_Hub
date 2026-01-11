@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-100 mb-8">Payment</h1>

    @if(isset($order))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Order Summary</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-2"><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p class="text-slate-600 dark:text-slate-400 mb-4"><strong>Total Amount:</strong> <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">TZS {{ number_format($order->total_amount, 2) }}</span></p>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-4 mb-4">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Order Items:</h3>
                @foreach($order->orderItems as $item)
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $item->orderable->name }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Qty: {{ $item->quantity }} × TZS {{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-semibold text-blue-700 dark:text-blue-300">TZS {{ number_format($item->quantity * $item->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($subscription))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Subscription Summary</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-2"><strong>Package:</strong> {{ $subscription->subscriptionPackage->name }}</p>
            <p class="text-slate-600 dark:text-slate-400 mb-2"><strong>Duration:</strong> {{ ucfirst($subscription->subscriptionPackage->duration_type) }}</p>
            <p class="text-slate-600 dark:text-slate-400 mb-4"><strong>Amount:</strong> <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">TZS {{ number_format($subscription->subscriptionPackage->price, 2) }}</span></p>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-6">Payment Method</h2>

        <form action="{{ route('payment.store') }}" method="POST">
            @csrf
            @if(isset($order))
                <input type="hidden" name="order_id" value="{{ $order->id }}">
            @endif
            @if(isset($subscription))
                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
            @endif
            @if(isset($package))
                <input type="hidden" name="package_id" value="{{ $package->id }}">
            @endif

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Payment Method
                </label>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="payment_method" id="mobile_money" value="mobile_money" checked class="text-blue-600 focus:ring-blue-500">
                        <label for="mobile_money" class="text-blue-900 dark:text-blue-100 font-medium">Mobile Money (ZenoPay)</label>
                    </div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-2 ml-6">
                        Pay securely via Mobile Money (Tigo Pesa, M-Pesa, Airtel Money)
                    </p>
                </div>
                <input type="hidden" name="payment_method" value="mobile_money">
            </div>

            <div class="mb-6">
                <label for="phone_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Mobile Phone Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="phone_number" 
                    id="phone_number" 
                    value="{{ old('phone_number', auth()->user()->phone ?? '') }}" 
                    required 
                    pattern="^(0|255)?[0-9]{9,10}$"
                    placeholder="0744963858 or 255744963858"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Format: 06XXXXXXXX, 07XXXXXXXX, 08XXXXXXXX, 09XXXXXXXX, or 255XXXXXXXXX (e.g., 0744963858, 255744963858)
                </p>
                @error('phone_number')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">How it works:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800 dark:text-blue-200">
                    <li>Click "Pay Now" to initiate the payment</li>
                    <li>You will receive a mobile money prompt on your phone</li>
                    <li>Confirm the payment on your phone</li>
                    <li>Our system will automatically verify your payment</li>
                    <li>You will be redirected to the confirmation page once payment is verified</li>
                </ol>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('home') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                    Pay Now
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
