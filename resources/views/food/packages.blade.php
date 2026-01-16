@extends('layouts.app')

@section('title', 'Packages - Monana Food')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <a href="{{ route('food.index') }}" class="text-sm text-[#a0a0a0] hover:text-white mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Monana Food
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">Subscription Packages</h1>
        <p class="text-xl text-[#a0a0a0] max-w-2xl mx-auto">Chagua package inayokufaa na upate bidhaa za jikoni kwa bei nafuu</p>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($packages as $package)
            <div class="card overflow-hidden {{ $loop->index === 1 ? 'ring-2 ring-[#ff6b35]' : '' }}">
                @if($loop->index === 1)
                    <div class="bg-[#ff6b35] text-white text-center py-2 text-sm font-bold">
                        POPULAR
                    </div>
                @endif
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $package->name }}</h3>
                    <p class="text-sm text-[#a0a0a0] mb-4">{{ $package->description }}</p>

                    <div class="mb-6">
                        <span class="text-4xl font-bold text-[#ff6b35]">TZS {{ number_format($package->base_price, 0) }}</span>
                        <span class="text-[#6b6b6b]">/{{ $package->duration_type }}</span>
                    </div>

                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-sm text-[#a0a0a0]">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $package->duration_days }} days
                        </li>
                        <li class="flex items-center text-sm text-[#a0a0a0]">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $package->deliveries_per_week }} deliveries per week
                        </li>
                        <li class="flex items-center text-sm text-[#a0a0a0]">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Delivery: {{ $package->getDeliveryDaysLabel() }}
                        </li>
                        <li class="flex items-center text-sm text-[#a0a0a0]">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Customizable items
                        </li>
                    </ul>

                    <!-- Included Items -->
                    <div class="mb-6 p-4 bg-[#1a1a1a] rounded-lg">
                        <h4 class="text-sm font-bold text-white mb-3">Included Items:</h4>
                        <div class="space-y-2">
                            @foreach($package->items->take(5) as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-[#a0a0a0]">{{ $item->product->name }}</span>
                                    <span class="text-white">{{ $item->default_quantity }} {{ $item->product->unit }}</span>
                                </div>
                            @endforeach
                            @if($package->items->count() > 5)
                                <p class="text-xs text-[#6b6b6b]">+{{ $package->items->count() - 5 }} more items</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('food.packages.show', $package) }}" 
                       class="block w-full text-center py-3 {{ $loop->index === 1 ? 'bg-[#ff6b35] hover:bg-[#e55a2b] text-white' : 'bg-[#333] hover:bg-[#444] text-white' }} font-medium rounded-lg transition-colors">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 card p-12 text-center">
                <p class="text-[#6b6b6b]">No packages available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
