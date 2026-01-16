@extends('layouts.app')

@section('title', 'Monana Cyber Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Monana Cyber Cafe</h1>
        <p class="text-lg text-[#a0a0a0] max-w-2xl mx-auto">Chagua muda wa mlo na uagize sasa!</p>
    </div>

    <!-- Meal Slots -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Chagua Muda wa Mlo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($mealSlots as $slotData)
                <div class="card p-6 {{ $slotData['is_open'] ? 'glow-cyber' : '' }} transition-all duration-300">
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

                    <p class="text-[#a0a0a0] mb-4">{{ $slotData['slot']->description }}</p>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Delivery:</span>
                            <span class="text-white">{{ $slotData['slot']->delivery_time_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6b6b6b]">Cutoff:</span>
                            <span class="text-white">{{ $slotData['cutoff_time'] }}</span>
                        </div>
                        @if($slotData['is_open'] && $slotData['time_remaining'])
                            <div class="flex justify-between">
                                <span class="text-[#6b6b6b]">Closes in:</span>
                                <span class="text-[#00d4aa] font-mono">{{ $slotData['time_remaining'] }}</span>
                            </div>
                        @endif
                    </div>

                    @if($slotData['is_open'])
                        <a href="{{ route('cyber.menu', ['slot' => $slotData['slot']->id]) }}" 
                           class="block w-full text-center py-3 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors">
                            Agiza Sasa
                        </a>
                    @else
                        <button disabled class="w-full py-3 bg-[#333] text-[#6b6b6b] font-medium rounded-lg cursor-not-allowed">
                            Imefungwa
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- How it Works -->
    <div class="card p-8">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Jinsi Inavyofanya Kazi</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-[#00d4aa]/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-[#00d4aa]">1</span>
                </div>
                <h3 class="font-medium text-white mb-2">Chagua Muda</h3>
                <p class="text-sm text-[#6b6b6b]">Asubuhi, Mchana, au Usiku</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-[#00d4aa]/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-[#00d4aa]">2</span>
                </div>
                <h3 class="font-medium text-white mb-2">Chagua Chakula</h3>
                <p class="text-sm text-[#6b6b6b]">Angalia menu na uchague</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-[#00d4aa]/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-[#00d4aa]">3</span>
                </div>
                <h3 class="font-medium text-white mb-2">Lipia</h3>
                <p class="text-sm text-[#6b6b6b]">M-Pesa, Tigo Pesa, Airtel Money</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-[#00d4aa]/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-[#00d4aa]">4</span>
                </div>
                <h3 class="font-medium text-white mb-2">Pokea</h3>
                <p class="text-sm text-[#6b6b6b]">Tunapakua mlangoni kwako</p>
            </div>
        </div>
    </div>
</div>
@endsection
