@extends('admin.layout')

@section('title', 'Subscriptions')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-100">Subscriptions Management</h1>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="bg-blue-600 dark:bg-blue-700 text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Package</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($subscriptions as $subscription)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $subscription->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $subscription->subscriptionPackage->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $subscription->start_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                        {{ $subscription->end_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($subscription->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($subscription->status === 'paused') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($subscription->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                            @else bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300
                            @endif
                        ">{{ ucfirst($subscription->status) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                            View
                        </a>
                        @if($subscription->status === 'active')
                            <form action="{{ route('admin.subscriptions.pause', $subscription) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">
                                    Pause
                                </button>
                            </form>
                        @elseif($subscription->status === 'paused')
                            <form action="{{ route('admin.subscriptions.resume', $subscription) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                    Resume
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-slate-600 dark:text-slate-400">No subscriptions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $subscriptions->links() }}
</div>
@endsection
