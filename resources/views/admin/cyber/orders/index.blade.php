@extends('admin.layout')

@section('title', 'Cyber Orders')
@section('subtitle', 'Manage Cyber Cafe orders')

@section('content')
<div class="space-y-6">
    <!-- Header with Map Link -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Cyber Orders</h1>
            <p class="text-sm text-[#a0a0a0] mt-1">Manage Cyber Cafe orders</p>
        </div>
        <a href="{{ route('admin.cyber.orders.map') }}" class="px-4 py-2 bg-[#00d4aa] hover:bg-[#00b894] text-black font-medium rounded-lg transition-colors text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            View on Map
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form action="{{ route('admin.cyber.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#00d4aa]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="on_delivery" {{ request('status') == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="meal_slot" class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#00d4aa]">
                <option value="">All Slots</option>
                @foreach($mealSlots as $slot)
                    <option value="{{ $slot->id }}" {{ request('meal_slot') == $slot->id ? 'selected' : '' }}>{{ $slot->display_name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}"
                class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#00d4aa]">
            <button type="submit" class="px-4 py-2 bg-[#00d4aa] text-black font-medium rounded-lg hover:bg-[#00b894] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'meal_slot', 'date']))
                <a href="{{ route('admin.cyber.orders.index') }}" class="px-4 py-2 bg-[#333] text-white font-medium rounded-lg hover:bg-[#444] transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2d2d2d]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Slot</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Items</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#333]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#2d2d2d] transition-colors">
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.cyber.orders.show', $order) }}" class="text-sm font-medium text-[#00d4aa] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-xs text-[#6b6b6b]">{{ $order->source }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-white">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $order->user->phone ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-[#a0a0a0]">
                                {{ $order->mealSlot->display_name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-[#a0a0a0]">
                                {{ $order->items->count() }} items
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-white">TZS {{ number_format($order->total_amount) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($order->status === 'pending') bg-yellow-500/20 text-yellow-500
                                    @elseif($order->status === 'approved') bg-blue-500/20 text-blue-500
                                    @elseif($order->status === 'preparing') bg-indigo-500/20 text-indigo-500
                                    @elseif($order->status === 'ready') bg-cyan-500/20 text-cyan-500
                                    @elseif($order->status === 'on_delivery') bg-purple-500/20 text-purple-500
                                    @elseif($order->status === 'delivered') bg-green-500/20 text-green-500
                                    @else bg-red-500/20 text-red-500
                                    @endif
                                ">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm text-[#6b6b6b]">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.cyber.orders.show', $order) }}" class="p-2 hover:bg-[#333] rounded-lg transition-colors inline-block">
                                    <svg class="w-4 h-4 text-[#a0a0a0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-[#6b6b6b]">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-[#333]">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
