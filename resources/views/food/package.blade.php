@extends('layouts.app')

@section('title', $package->name . ' - Monana Market')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('food.packages') }}" class="inline-flex items-center text-[#ff6b35] hover:text-[#e55a2b] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Packages
        </a>
    </div>

    <!-- Package Header -->
    <div class="card p-6 mb-6 glow-food">
        <div class="text-center mb-6">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">{{ $package->name }}</h1>
            <p class="text-lg text-[#a0a0a0] max-w-2xl mx-auto">{{ $package->description }}</p>
        </div>

        <!-- Pricing -->
        <div class="text-center mb-6 pb-6 border-b border-[#333]">
            <div class="mb-2">
                <span class="text-5xl font-bold text-[#ff6b35]">TZS {{ number_format($package->base_price, 0) }}</span>
            </div>
            <span class="text-[#6b6b6b] text-lg">/{{ $package->duration_type }}</span>
        </div>

        <!-- Package Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-[#1a1a1a] rounded-lg">
                <p class="text-xs text-[#6b6b6b] mb-1">Duration</p>
                <p class="text-sm font-medium text-white">{{ $package->duration_days }} days</p>
            </div>
            <div class="p-4 bg-[#1a1a1a] rounded-lg">
                <p class="text-xs text-[#6b6b6b] mb-1">Deliveries per Week</p>
                <p class="text-sm font-medium text-white">{{ $package->deliveries_per_week }} deliveries</p>
            </div>
            <div class="p-4 bg-[#1a1a1a] rounded-lg md:col-span-2">
                <p class="text-xs text-[#6b6b6b] mb-1">Delivery Days</p>
                <p class="text-sm font-medium text-white">{{ $package->getDeliveryDaysLabel() }}</p>
            </div>
        </div>

        <!-- Features List -->
        <ul class="space-y-3 mb-6">
            <li class="flex items-center text-sm text-[#a0a0a0]">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ $package->duration_days }} days subscription
            </li>
            <li class="flex items-center text-sm text-[#a0a0a0]">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ $package->deliveries_per_week }} deliveries per week
            </li>
            <li class="flex items-center text-sm text-[#a0a0a0]">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Customizable items before delivery
            </li>
            <li class="flex items-center text-sm text-[#a0a0a0]">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Pause or resume anytime
            </li>
        </ul>
    </div>

    <!-- Included Items -->
    <div class="card p-6 mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">Included Items</h2>
        <p class="text-sm text-[#6b6b6b] mb-6">All items included in this package. You can customize quantities before each delivery.</p>
        
        @if($package->items->count() > 0)
            <div class="space-y-3">
                @foreach($package->items as $item)
                    <div class="flex items-center justify-between p-4 bg-[#1a1a1a] rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-[#ff6b35]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#ff6b35]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-white">{{ $item->product->name }}</h3>
                                <p class="text-sm text-[#6b6b6b]">{{ $item->product->description ?? 'Kitchen essential' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-[#a0a0a0]">Default Quantity</p>
                            <p class="text-lg font-bold text-[#ff6b35]">{{ $item->default_quantity }} {{ $item->product->unit }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-[#6b6b6b]">No items included in this package.</p>
            </div>
        @endif
    </div>

    <!-- Subscribe Button -->
    <div class="card p-6 text-center">
        @auth
            <form method="POST" action="{{ route('food.packages.subscribe', $package) }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-bold text-lg rounded-lg transition-colors">
                    Subscribe to This Package
                </button>
            </form>
            <p class="text-sm text-[#6b6b6b] mt-4">You'll be able to customize items before each delivery.</p>
        @else
            <div class="mb-4">
                <p class="text-lg text-white mb-2">Ready to subscribe?</p>
                <p class="text-sm text-[#a0a0a0] mb-6">Sign in or create an account to get started.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#333] hover:bg-[#404040] text-white font-medium rounded-lg transition-colors">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#ff6b35] hover:bg-[#e55a2b] text-white font-bold rounded-lg transition-colors">
                    Create Account
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
