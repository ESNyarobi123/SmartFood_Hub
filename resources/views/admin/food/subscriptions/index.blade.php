@extends('admin.layout')

@section('title', 'Subscriptions')
@section('subtitle', 'Manage customer subscriptions')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="card p-4">
        <form action="{{ route('admin.food.subscriptions.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <select name="package" class="px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35]">
                <option value="">All Packages</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}" {{ request('package') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-[#ff6b35] text-white font-medium rounded-lg hover:bg-[#e55a2b] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'package']))
                <a href="{{ route('admin.food.subscriptions.index') }}" class="px-4 py-2 bg-[#333] text-white font-medium rounded-lg hover:bg-[#444] transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2d2d2d]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Package</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#6b6b6b] uppercase">Source</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#6b6b6b] uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#333]">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-[#2d2d2d] transition-colors">
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-white">{{ $subscription->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $subscription->user->phone ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-white">{{ $subscription->package->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-[#ff6b35]">TZS {{ number_format($subscription->package->base_price ?? 0) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-white">{{ $subscription->start_date->format('M d') }} - {{ $subscription->end_date->format('M d, Y') }}</p>
                                <p class="text-xs text-[#6b6b6b]">{{ $subscription->end_date->diffForHumans() }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($subscription->status === 'active') bg-green-500/20 text-green-500
                                    @elseif($subscription->status === 'paused') bg-yellow-500/20 text-yellow-500
                                    @elseif($subscription->status === 'pending') bg-blue-500/20 text-blue-500
                                    @elseif($subscription->status === 'cancelled') bg-red-500/20 text-red-500
                                    @else bg-gray-500/20 text-gray-500
                                    @endif
                                ">{{ ucfirst($subscription->status) }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm text-[#a0a0a0]">
                                {{ ucfirst($subscription->source) }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.food.subscriptions.show', $subscription) }}" class="p-2 hover:bg-[#333] rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4 text-[#a0a0a0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-[#6b6b6b]">
                                No subscriptions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-[#333]">
                {{ $subscriptions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
