@extends('admin.layout')

@section('title', 'Cyber Cafe Dashboard')
@section('subtitle', 'Manage cooked food orders and meal slots')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-[#00d4aa]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Pending Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#00d4aa]/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Today's Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['today_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Delivered Today</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['delivered_today'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-5 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#6b6b6b] uppercase tracking-wider">Today's Revenue</p>
                    <p class="text-2xl font-bold text-white mt-1">TZS {{ number_format($stats['today_revenue']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Slots Status -->
    <div class="card overflow-hidden">
        <div class="p-4 border-b border-[#333]">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-white">Meal Slots Status</h3>
                <a href="{{ route('admin.cyber.meal-slots.index') }}" class="text-sm text-[#00d4aa] hover:underline">Manage Slots</a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#333]">
            @foreach($mealSlots as $slotData)
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-bold text-white">{{ $slotData['slot']->display_name }}</h4>
                        @if($slotData['is_open'])
                            <span class="flex items-center text-xs font-medium text-green-500">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                OPEN
                            </span>
                        @else
                            <span class="flex items-center text-xs font-medium text-red-500">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                CLOSED
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-[#6b6b6b] mb-2">{{ $slotData['slot']->delivery_time_label }}</p>
                    @if($slotData['is_open'] && $slotData['time_remaining'])
                        <p class="text-xs text-[#00d4aa]">Closes in: {{ $slotData['time_remaining'] }}</p>
                    @endif
                    <p class="text-xs text-[#6b6b6b] mt-2">Cutoff: {{ $slotData['cutoff_time'] }}</p>
                    
                    @if(isset($ordersBySlot[$slotData['slot']->id]))
                        <div class="mt-3 pt-3 border-t border-[#333]">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6b6b6b]">Today's Orders:</span>
                                <span class="text-white font-medium">{{ $ordersBySlot[$slotData['slot']->id]['total'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6b6b6b]">Pending:</span>
                                <span class="text-yellow-500 font-medium">{{ $ordersBySlot[$slotData['slot']->id]['pending'] }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="card p-5">
            <h3 class="font-bold text-white mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.cyber.menu.create') }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-[#00d4aa]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">Add Menu Item</span>
                    </div>
                    <svg class="w-4 h-4 text-[#6b6b6b] group-hover:text-[#00d4aa] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.cyber.orders.index', ['status' => 'pending']) }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">View Pending Orders</span>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-bold bg-yellow-500 text-black rounded-full">{{ $stats['pending_orders'] }}</span>
                </a>
                <a href="{{ route('admin.cyber.menu.index') }}" class="flex items-center justify-between p-3 bg-[#2d2d2d] hover:bg-[#333] rounded-lg transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-white">Manage Menu</span>
                    </div>
                    <span class="text-xs text-[#6b6b6b]">{{ $stats['total_menu_items'] }} items</span>
                </a>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="lg:col-span-2 card overflow-hidden">
            <div class="p-4 border-b border-[#333]">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-white">Recent Orders</h3>
                    <a href="{{ route('admin.cyber.orders.index') }}" class="text-sm text-[#00d4aa] hover:underline">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#2d2d2d]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Order</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Slot</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#333]">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-[#2d2d2d] transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.cyber.orders.show', $order) }}" class="text-sm font-medium text-[#00d4aa] hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-white">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="px-4 py-3 text-sm text-[#a0a0a0]">{{ $order->mealSlot->display_name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-white">TZS {{ number_format($order->total_amount) }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($order->status === 'pending') bg-yellow-500/20 text-yellow-500
                                        @elseif($order->status === 'approved') bg-blue-500/20 text-blue-500
                                        @elseif($order->status === 'preparing') bg-indigo-500/20 text-indigo-500
                                        @elseif($order->status === 'ready') bg-cyan-500/20 text-cyan-500
                                        @elseif($order->status === 'delivered') bg-green-500/20 text-green-500
                                        @else bg-gray-500/20 text-gray-500
                                        @endif
                                    ">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[#6b6b6b]">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
