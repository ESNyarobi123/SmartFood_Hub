@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-100">Order Details</h1>
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
        ← Back to Orders
    </a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Order Information</h2>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Order Number:</strong></p>
            <p class="text-blue-900 dark:text-blue-100 font-mono">{{ $order->order_number }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Status:</strong></p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                @if($order->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($order->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                @endif
            ">{{ ucfirst($order->status) }}</span>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Customer:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $order->user->name ?? 'Guest' }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Total Amount:</strong></p>
            <p class="text-blue-900 dark:text-blue-100 text-xl font-bold">TZS {{ number_format($order->total_amount, 2) }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Source:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ ucfirst($order->source) }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Date:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $order->created_at->format('F d, Y H:i') }}</p>
        </div>
    </div>
    <div class="mb-4">
        <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Delivery Address:</strong></p>
        <p class="text-blue-900 dark:text-blue-100">{{ $order->delivery_address }}</p>
    </div>
    @if($order->notes)
        <div class="mb-4">
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Notes:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $order->notes }}</p>
        </div>
    @endif
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Order Items</h2>
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="bg-blue-50 dark:bg-slate-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Total</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $item->orderable->name }}
                        @if($item->notes)
                            <br><span class="text-xs text-slate-500 italic">{{ $item->notes }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">TZS {{ number_format($item->price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-700 dark:text-blue-300">TZS {{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Update Status</h2>
    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
        @csrf
        @method('PATCH')
        <div class="flex items-center space-x-4">
            <select name="status" required class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $order->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                Update Status
            </button>
        </div>
    </form>

    @if($order->status === 'approved' || $order->status === 'preparing' || $order->status === 'ready')
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Assign Delivery</h3>
        <form action="{{ route('admin.orders.assign', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex items-center space-x-4">
                <select name="assigned_to" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select delivery staff</option>
                    @foreach($deliveryStaff ?? [] as $staff)
                        <option value="{{ $staff->id }}" {{ $order->assigned_to === $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                    Assign
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
