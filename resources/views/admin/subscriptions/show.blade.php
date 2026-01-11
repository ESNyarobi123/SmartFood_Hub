@extends('admin.layout')

@section('title', 'Subscription Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-100">Subscription Details</h1>
    <a href="{{ route('admin.subscriptions.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
        ← Back to Subscriptions
    </a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Subscription Information</h2>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Customer:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $subscription->user->name }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Package:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $subscription->subscriptionPackage->name }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Start Date:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $subscription->start_date->format('F d, Y') }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>End Date:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $subscription->end_date->format('F d, Y') }}</p>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Status:</strong></p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                @if($subscription->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                @elseif($subscription->status === 'paused') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($subscription->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                @else bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300
                @endif
            ">{{ ucfirst($subscription->status) }}</span>
        </div>
        <div>
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Price:</strong></p>
            <p class="text-blue-900 dark:text-blue-100 text-xl font-bold">TZS {{ number_format($subscription->subscriptionPackage->price, 2) }}</p>
        </div>
    </div>
    @if($subscription->notes)
        <div class="mb-4">
            <p class="text-slate-600 dark:text-slate-400 mb-1"><strong>Notes:</strong></p>
            <p class="text-blue-900 dark:text-blue-100">{{ $subscription->notes }}</p>
        </div>
    @endif
</div>

@if($subscription->delivery_schedule)
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Delivery Schedule</h2>
        <ul class="list-disc list-inside space-y-2 text-slate-600 dark:text-slate-400">
            @foreach($subscription->delivery_schedule as $date)
                <li>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Deliveries</h2>
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="bg-blue-50 dark:bg-slate-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Scheduled Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Delivered Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 dark:text-blue-100 uppercase tracking-wider">Assigned To</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($subscription->deliveries as $delivery)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $delivery->scheduled_date->format('F d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $delivery->delivered_date ? $delivery->delivered_date->format('F d, Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($delivery->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($delivery->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($delivery->status === 'skipped') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                            @else bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300
                            @endif
                        ">{{ ucfirst($delivery->status) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $delivery->assignedUser->name ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-slate-600 dark:text-slate-400">No deliveries scheduled yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Actions</h2>
    <div class="flex space-x-4">
        @if($subscription->status === 'active')
            <form action="{{ route('admin.subscriptions.pause', $subscription) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md font-medium">
                    Pause Subscription
                </button>
            </form>
        @elseif($subscription->status === 'paused')
            <form action="{{ route('admin.subscriptions.resume', $subscription) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                    Resume Subscription
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
