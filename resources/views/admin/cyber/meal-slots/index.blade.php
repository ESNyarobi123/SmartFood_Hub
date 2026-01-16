@extends('admin.layout')

@section('title', 'Meal Slots')
@section('subtitle', 'Configure meal ordering times')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($mealSlots as $slotData)
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white">{{ $slotData['slot']->display_name }}</h3>
                    @if($slotData['is_open'])
                        <span class="flex items-center text-sm font-medium text-green-500">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            OPEN
                        </span>
                    @else
                        <span class="flex items-center text-sm font-medium text-red-500">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            CLOSED
                        </span>
                    @endif
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#6b6b6b]">Order Start:</span>
                        <span class="text-white">{{ \Carbon\Carbon::parse($slotData['slot']->order_start_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#6b6b6b]">Order End:</span>
                        <span class="text-white">{{ $slotData['cutoff_time'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#6b6b6b]">Delivery:</span>
                        <span class="text-white">{{ $slotData['slot']->delivery_time_label }}</span>
                    </div>
                    @if($slotData['is_open'] && $slotData['time_remaining'])
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Closes in:</span>
                            <span class="text-[#00d4aa] font-mono">{{ $slotData['time_remaining'] }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-[#333]">
                    <p class="text-xs text-[#6b6b6b] mb-3">{{ $slotData['slot']->description }}</p>
                </div>

                <div class="mt-4 flex items-center space-x-2">
                    <a href="{{ route('admin.cyber.meal-slots.edit', $slotData['slot']) }}" 
                        class="flex-1 text-center px-4 py-2 bg-[#2d2d2d] hover:bg-[#333] text-white text-sm font-medium rounded-lg transition-colors">
                        Edit Times
                    </a>
                    <form action="{{ route('admin.cyber.meal-slots.toggle', $slotData['slot']) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium rounded-lg transition-colors
                            {{ $slotData['slot']->is_active ? 'bg-red-500/20 hover:bg-red-500/30 text-red-500' : 'bg-green-500/20 hover:bg-green-500/30 text-green-500' }}">
                            {{ $slotData['slot']->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-white mb-4">How Meal Slots Work</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <h4 class="font-medium text-[#00d4aa] mb-2">Asubuhi (Breakfast)</h4>
                <p class="text-[#a0a0a0]">Customers can order breakfast items during the night/early morning. Orders are delivered the following morning.</p>
            </div>
            <div>
                <h4 class="font-medium text-[#00d4aa] mb-2">Mchana (Lunch)</h4>
                <p class="text-[#a0a0a0]">Morning orders for lunch delivery. The slot closes before noon to allow preparation.</p>
            </div>
            <div>
                <h4 class="font-medium text-[#00d4aa] mb-2">Usiku (Dinner)</h4>
                <p class="text-[#a0a0a0]">Afternoon/evening orders for dinner delivery. Closes early evening for same-day delivery.</p>
            </div>
        </div>
    </div>
</div>
@endsection
