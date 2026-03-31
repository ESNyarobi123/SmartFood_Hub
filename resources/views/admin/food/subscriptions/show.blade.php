@extends('admin.layout')

@section('title', 'Subscription #' . $subscription->id)
@section('subtitle', 'View subscription details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.food.subscriptions.index') }}" class="flex items-center text-sm text-[#a0a0a0] hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Subscriptions
        </a>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2">
            @if($subscription->status === 'active')
                <form action="{{ route('admin.food.subscriptions.pause', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to pause this subscription?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500/20 text-yellow-500 font-medium rounded-lg hover:bg-yellow-500/30 transition-colors text-sm">
                        Pause
                    </button>
                </form>
            @endif

            @if($subscription->status === 'paused')
                <form action="{{ route('admin.food.subscriptions.resume', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to resume this subscription?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-500/20 text-green-500 font-medium rounded-lg hover:bg-green-500/30 transition-colors text-sm">
                        Resume
                    </button>
                </form>
            @endif

            @if(in_array($subscription->status, ['active', 'paused', 'pending']))
                <form action="{{ route('admin.food.subscriptions.cancel', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this subscription? This cannot be undone.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-500 font-medium rounded-lg hover:bg-red-500/30 transition-colors text-sm">
                        Cancel
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white">Subscription Status</h3>
                    <span class="text-xs px-3 py-1 rounded-full
                        @if($subscription->status === 'active') bg-green-500/20 text-green-500
                        @elseif($subscription->status === 'paused') bg-yellow-500/20 text-yellow-500
                        @elseif($subscription->status === 'pending') bg-blue-500/20 text-blue-500
                        @elseif($subscription->status === 'cancelled') bg-red-500/20 text-red-500
                        @elseif($subscription->status === 'expired') bg-gray-500/20 text-gray-400
                        @else bg-gray-500/20 text-gray-500
                        @endif
                    ">{{ ucfirst($subscription->status) }}</span>
                </div>

                <!-- Update Status Form -->
                <form action="{{ route('admin.food.subscriptions.update-status', $subscription) }}" method="POST" class="flex items-center space-x-4 mb-6">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 px-4 py-2 bg-[#2d2d2d] border border-[#333] rounded-lg text-white text-sm focus:border-[#ff6b35]">
                        <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ $subscription->status == 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-[#ff6b35] text-white font-medium rounded-lg hover:bg-[#e55a2b] transition-colors">
                        Update
                    </button>
                </form>

                @if($subscription->status === 'expired')
                    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                        <p class="text-sm text-red-400">
                            <strong>Subscription Expired</strong> — Deliveries have stopped. The customer needs to subscribe again or admin can reactivate to "Active".
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-[#6b6b6b]">Start Date</p>
                        <p class="text-sm text-white font-medium">{{ $subscription->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#6b6b6b]">End Date</p>
                        <p class="text-sm text-white font-medium">{{ $subscription->end_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#6b6b6b]">Days Remaining</p>
                        <p class="text-sm font-medium {{ $subscription->getDaysRemaining() === 0 ? 'text-red-400' : 'text-white' }}">
                            {{ $subscription->getDaysRemaining() }} days
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-[#6b6b6b]">Source</p>
                        <p class="text-sm text-white font-medium">{{ ucfirst($subscription->source) }}</p>
                    </div>
                </div>

                @if($subscription->paused_at || $subscription->resumed_at || $subscription->expired_at)
                    <div class="mt-4 pt-4 border-t border-[#333] grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @if($subscription->paused_at)
                            <div>
                                <p class="text-xs text-[#6b6b6b]">Paused At</p>
                                <p class="text-sm text-yellow-500">{{ $subscription->paused_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($subscription->resumed_at)
                            <div>
                                <p class="text-xs text-[#6b6b6b]">Resumed At</p>
                                <p class="text-sm text-green-500">{{ $subscription->resumed_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($subscription->expired_at)
                            <div>
                                <p class="text-xs text-[#6b6b6b]">Expired At</p>
                                <p class="text-sm text-red-400">{{ $subscription->expired_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Package Items -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-[#333]">
                    <h3 class="font-bold text-white">Package Items</h3>
                    <p class="text-xs text-[#6b6b6b] mt-1">{{ $subscription->package->name ?? 'Unknown Package' }} — TZS {{ number_format($subscription->package->base_price ?? 0) }}</p>
                </div>
                <div class="divide-y divide-[#333]">
                    @forelse($subscription->package->items ?? [] as $item)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-[#333] rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-[#6b6b6b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $item->product->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-[#6b6b6b]">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-sm text-[#6b6b6b]">No items in this package</div>
                    @endforelse
                </div>
            </div>

            <!-- Customizations -->
            @if($subscription->customizations->count())
                <div class="card overflow-hidden">
                    <div class="p-4 border-b border-[#333]">
                        <h3 class="font-bold text-white">Customizations</h3>
                    </div>
                    <div class="divide-y divide-[#333]">
                        @foreach($subscription->customizations as $customization)
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($customization->action_type === 'pause') bg-yellow-500/20 text-yellow-500
                                        @elseif($customization->action_type === 'swap') bg-blue-500/20 text-blue-500
                                        @elseif($customization->action_type === 'remove') bg-red-500/20 text-red-500
                                        @elseif($customization->action_type === 'add') bg-green-500/20 text-green-500
                                        @else bg-gray-500/20 text-gray-500
                                        @endif
                                    ">{{ $customization->getActionLabel() }}</span>
                                    <span class="text-xs text-[#6b6b6b]">{{ $customization->delivery_date->format('M d, Y') }}</span>
                                </div>
                                <div class="text-sm text-[#a0a0a0]">
                                    @if($customization->action_type === 'swap')
                                        <p>{{ $customization->originalProduct->name ?? 'Unknown' }} → {{ $customization->newProduct->name ?? 'Unknown' }}</p>
                                        @if($customization->price_adjustment != 0)
                                            <p class="text-xs mt-1">Price adjustment: <span class="{{ $customization->price_adjustment > 0 ? 'text-green-500' : 'text-red-500' }}">TZS {{ number_format($customization->price_adjustment) }}</span></p>
                                        @endif
                                    @elseif($customization->action_type === 'remove')
                                        <p>Removed: {{ $customization->originalProduct->name ?? 'Unknown' }}</p>
                                    @elseif($customization->action_type === 'add')
                                        <p>Added: {{ $customization->newProduct->name ?? 'Unknown' }} ({{ $customization->new_quantity }})</p>
                                    @elseif($customization->action_type === 'pause')
                                        <p>Delivery paused for this date</p>
                                    @endif
                                    @if($customization->notes)
                                        <p class="text-xs text-[#6b6b6b] mt-1">{{ $customization->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($subscription->notes)
                <div class="card p-6">
                    <h3 class="font-bold text-white mb-3">Notes</h3>
                    <p class="text-sm text-[#a0a0a0]">{{ $subscription->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Customer</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-[#6b6b6b]">Name</p>
                        <p class="text-white">{{ $subscription->user->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Phone</p>
                        <p class="text-white">{{ $subscription->user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Email</p>
                        <p class="text-white">{{ $subscription->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Delivery</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-[#6b6b6b]">Address</p>
                        <p class="text-white">{{ $subscription->delivery_address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Subscription Details -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-[#6b6b6b]">Subscription ID</p>
                        <p class="text-white font-mono">#{{ $subscription->id }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Package</p>
                        <p class="text-white">{{ $subscription->package->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-[#6b6b6b]">Created</p>
                        <p class="text-white">{{ $subscription->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Payments</h3>
                @if($subscription->payments->count())
                    <div class="space-y-3">
                        @foreach($subscription->payments as $payment)
                            <div class="p-3 bg-[#2d2d2d] rounded-lg">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-white">TZS {{ number_format($payment->amount) }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        @if($payment->status === 'paid') bg-green-500/20 text-green-500
                                        @elseif($payment->status === 'pending') bg-yellow-500/20 text-yellow-500
                                        @elseif($payment->status === 'failed') bg-red-500/20 text-red-500
                                        @else bg-gray-500/20 text-gray-500
                                        @endif
                                    ">{{ ucfirst($payment->status) }}</span>
                                </div>
                                <p class="text-xs text-[#6b6b6b]">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                                @if($payment->phone)
                                    <p class="text-xs text-[#6b6b6b]">Phone: {{ $payment->phone }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-[#6b6b6b]">No payments recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
