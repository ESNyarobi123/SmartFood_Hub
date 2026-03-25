@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="text-[#9ca3af] hover:text-white transition-colors">Home</a>
        <svg class="w-4 h-4 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-white font-medium">Payment</span>
    </nav>

    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6 sm:mb-8">Payment</h1>

    @if(isset($order))
        <div class="card p-4 sm:p-6 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-white mb-4">Order Summary</h2>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-4 mb-4">
                <p class="text-sm text-[#9ca3af]"><span class="text-[#6b6b6b]">Order:</span> <span class="font-mono text-white">{{ $order->order_number }}</span></p>
                <p class="text-xl sm:text-2xl font-bold text-[#ff6b35]">TZS {{ number_format($order->total_amount, 2) }}</p>
            </div>

            <div class="border-t border-[#333] pt-4">
                <h3 class="text-sm font-semibold text-[#9ca3af] mb-3">Order Items:</h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#333] gap-1">
                            <div>
                                <p class="font-medium text-white text-sm">{{ $item->orderable->name }}</p>
                                <p class="text-xs text-[#6b6b6b]">Qty: {{ $item->quantity }} × TZS {{ number_format($item->price, 2) }}</p>
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
            <h2 class="text-lg sm:text-xl font-bold text-white mb-4">Subscription Summary</h2>
            <div class="space-y-2">
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1">
                    <span class="text-sm text-[#6b6b6b]">Package</span>
                    <span class="text-sm font-medium text-white">{{ $subscription->subscriptionPackage->name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1">
                    <span class="text-sm text-[#6b6b6b]">Duration</span>
                    <span class="text-sm font-medium text-white">{{ ucfirst($subscription->subscriptionPackage->duration_type) }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 pt-2 border-t border-[#333]">
                    <span class="text-sm text-[#6b6b6b]">Amount</span>
                    <span class="text-xl sm:text-2xl font-bold text-[#ff6b35]">TZS {{ number_format($subscription->subscriptionPackage->price, 2) }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="card p-4 sm:p-6" x-data="{ submitting: false }">
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6">Payment Method</h2>

        <form action="{{ route('payment.store') }}" method="POST" @submit="submitting = true">
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
                <label class="block text-sm font-medium text-white mb-2">Payment Method</label>
                <div class="bg-[#1a1a1a] border border-[#333] rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="payment_method_display" id="mobile_money" value="mobile_money" checked class="text-[#ff6b35] focus:ring-[#ff6b35] bg-[#2d2d2d] border-[#444]">
                        <label for="mobile_money" class="text-white font-medium text-sm">Mobile Money (ZenoPay)</label>
                    </div>
                    <p class="text-xs text-[#9ca3af] mt-2 ml-6">Pay securely via Tigo Pesa, M-Pesa, or Airtel Money</p>
                </div>
                <input type="hidden" name="payment_method" value="mobile_money">
            </div>

            <div class="mb-6">
                <label for="phone_number" class="block text-sm font-medium text-white mb-2">
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
                    class="w-full px-4 py-3 bg-[#2d2d2d] border border-[#333] rounded-lg text-white placeholder-[#6b6b6b] focus:outline-none focus:ring-2 focus:ring-[#ff6b35] focus:border-[#ff6b35] transition-all text-sm"
                >
                <p class="text-xs text-[#6b6b6b] mt-1.5">
                    Format: 06XXXXXXXX, 07XXXXXXXX, 08XXXXXXXX, 09XXXXXXXX, or 255XXXXXXXXX
                </p>
                @error('phone_number')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-[#1a1a1a] border border-[#333] rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-white mb-3">How it works:</h3>
                <ol class="space-y-2 text-xs text-[#9ca3af]">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 bg-[#ff6b35]/20 text-[#ff6b35] rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">1</span>
                        Click "Pay Now" to initiate the payment
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 bg-[#ff6b35]/20 text-[#ff6b35] rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">2</span>
                        You will receive a mobile money prompt on your phone
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 bg-[#ff6b35]/20 text-[#ff6b35] rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">3</span>
                        Confirm the payment on your phone
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 bg-[#ff6b35]/20 text-[#ff6b35] rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">4</span>
                        Our system will automatically verify your payment
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 bg-[#ff6b35]/20 text-[#ff6b35] rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">5</span>
                        You will be redirected to the confirmation page
                    </li>
                </ol>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-[#333] hover:bg-[#444] text-white rounded-lg font-medium text-sm text-center transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        :disabled="submitting"
                        :class="submitting ? 'opacity-60 cursor-not-allowed' : 'hover:bg-[#e55a2b]'"
                        class="px-6 py-3 bg-[#ff6b35] text-white rounded-lg font-bold text-sm transition-all flex items-center justify-center gap-2">
                    <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="submitting ? 'Processing...' : 'Pay Now'">Pay Now</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
